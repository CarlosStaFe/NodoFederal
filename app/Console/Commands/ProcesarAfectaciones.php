<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Models\Localidad;
use App\Models\Operacion;
use App\Models\Socio;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcesarAfectaciones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'procesar:afectaciones {--test=0 : Numero de registros de prueba (0 = todos)} {--file=afectaciones.json : Archivo JSON dentro de storage/app}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Procesa el archivo afectaciones.json para crear clientes y operaciones';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $archivoJson = basename((string) $this->option('file'));
        if (!str_ends_with(strtolower($archivoJson), '.json')) {
            $archivoJson .= '.json';
        }

        $this->info('Iniciando procesamiento de afectaciones desde: ' . $archivoJson);

        $rutaArchivo = storage_path('app/' . $archivoJson);
        if (!file_exists($rutaArchivo)) {
            $this->error("El archivo {$archivoJson} no existe en: {$rutaArchivo}");
            return 1;
        }

        $contenido = file_get_contents($rutaArchivo);
        $afectaciones = json_decode($contenido, true);

        if (!$afectaciones) {
            $this->error('Error al leer el archivo JSON');
            return 1;
        }

        $testLimit = (int) $this->option('test');
        if ($testLimit > 0) {
            $afectaciones = array_slice($afectaciones, 0, $testLimit);
            $this->info("Modo de prueba: procesando solo {$testLimit} registros");
        }

        $registrosConCuilInvalido = [];
        foreach ($afectaciones as $indice => $afectacion) {
            $cuilOriginal = (string) ($afectacion['CUIL'] ?? '');
            $cuilLimpio = preg_replace('/\D/', '', $cuilOriginal);

            if (strlen($cuilLimpio) !== 11) {
                $registrosConCuilInvalido[] = [
                    'indice' => $indice + 1,
                    'id' => $afectacion['ID'] ?? 'N/A',
                    'cuil' => $cuilOriginal,
                ];
            }
        }

        if (!empty($registrosConCuilInvalido)) {
            $this->error('Se detectaron CUIL invalidos. Todos deben tener 11 digitos.');
            foreach ($registrosConCuilInvalido as $errorCuil) {
                $this->line(
                    '- Fila ' . $errorCuil['indice'] .
                    ' | ID: ' . $errorCuil['id'] .
                    ' | CUIL: ' . ($errorCuil['cuil'] !== '' ? $errorCuil['cuil'] : '[vacio]')
                );
            }

            return 1;
        }

        $registrosConReferenciaDuplicada = $this->obtenerReferenciasDuplicadasPorSocio($afectaciones);

        if (!empty($registrosConReferenciaDuplicada)) {
            $this->error('Se detectaron referencias existentes para el socio correspondiente en la tabla operaciones.');
            foreach ($registrosConReferenciaDuplicada as $errorReferencia) {
                $this->line(
                    '- Fila ' . $errorReferencia['indice'] .
                    ' | ID: ' . $errorReferencia['id'] .
                    ' | Socio ID: ' . $errorReferencia['socio_id'] .
                    ' | Referencia: ' . $errorReferencia['referencia']
                );
            }

            return 1;
        }

        $this->info('Total de registros a procesar: ' . count($afectaciones));

        $localidadDefault = Localidad::first();
        $usuarioDefault = User::first();

        if (!$localidadDefault || !$usuarioDefault) {
            $this->error('No se encontraron localidades o usuarios en la base de datos');
            return 1;
        }

        $clientesCreados = 0;
        $operacionesCreadas = 0;
        $errores = 0;
        $ultimoNumeroOperacion = (int) (Operacion::max('numero') ?? 0);

        $progressBar = $this->output->createProgressBar(count($afectaciones));
        $progressBar->start();

        foreach ($afectaciones as $afectacion) {
            try {
                $cliente = Cliente::where('cuit', $afectacion['CUIL'] ?? '')->first();

                if (!$cliente) {
                    $cliente = $this->crearCliente($afectacion, $localidadDefault->id);
                    if ($cliente) {
                        $clientesCreados++;
                    }
                }

                if ($cliente) {
                    $this->actualizarEstadoClienteDesdeAfectacion($cliente, $afectacion);

                    $operacion = $this->crearOperacion(
                        $afectacion,
                        $cliente->id,
                        $usuarioDefault->id,
                        $ultimoNumeroOperacion + 1
                    );
                    if ($operacion) {
                        $operacionesCreadas++;
                        $ultimoNumeroOperacion++;
                    }
                }
            } catch (\Exception $e) {
                $errores++;
                Log::error('Error procesando afectacion ID: ' . ($afectacion['ID'] ?? 'N/A'), [
                    'error' => $e->getMessage(),
                    'data' => $afectacion,
                ]);
            }

            $progressBar->advance();
        }

        $progressBar->finish();

        $this->newLine();
        $this->info('Procesamiento completado:');
        $this->info("- Clientes creados: {$clientesCreados}");
        $this->info("- Operaciones creadas: {$operacionesCreadas}");
        if ($errores > 0) {
            $this->warn("- Errores: {$errores}");
        }

        return 0;
    }

    private function crearCliente(array $afectacion, int $localidadDefaultId): ?Cliente
    {
        try {
            $cuil = preg_replace('/\D/', '', (string) ($afectacion['CUIL'] ?? ''));
            $documento = '';

            if (strlen($cuil) === 11) {
                $documento = substr($cuil, 2, 8);
            } else {
                $documento = (string) ($afectacion['DNI'] ?? '');
            }

            $documentoInt = 0;
            if (!empty($documento)) {
                $documentoInt = (int) $documento;
                if ($documentoInt > 2147483647) {
                    $documentoInt = 0;
                }
            }

            $cliente = Cliente::create([
                'tipodoc' => 'DNI',
                'documento' => $documentoInt,
                'sexo' => '-',
                'cuit' => $cuil ?: '-',
                'apelnombres' => mb_substr($afectacion['TITULAR'] ?? '-', 0, 50),
                'nacimiento' => null,
                'nacionalidad' => 'Argentina',
                'domicilio' => '-',
                'cod_postal_id' => $localidadDefaultId,
                'telefono' => '-',
                'email' => null,
                'estado' => $this->obtenerEstadoClienteDesdeAfectacion($afectacion),
                'fechaestado' => $this->parsearFechaDeuda($afectacion['FECHA DEUDA'] ?? null),
                'observacion' => 'Importado desde afectaciones.json',
            ]);

            return $cliente;
        } catch (\Exception $e) {
            Log::error('Error creando cliente: ' . $e->getMessage(), $afectacion);
            return null;
        }
    }

    private function actualizarEstadoClienteDesdeAfectacion(Cliente $cliente, array $afectacion): void
    {
        $cliente->estado = $this->obtenerEstadoClienteDesdeAfectacion($afectacion);
        $cliente->fechaestado = $this->parsearFechaDeuda($afectacion['FECHA DEUDA'] ?? null);
        $cliente->save();
    }

    private function obtenerEstadoClienteDesdeAfectacion(array $afectacion): string
    {
        $estado = trim((string) ($afectacion['CODIGO DE ATRASO'] ?? ''));
        if ($estado === '') {
            $estado = 'PENDIENTE';
        }

        return mb_substr($estado, 0, 20);
    }

    private function parsearFechaDeuda($fecha): string
    {
        if (!empty($fecha)) {
            try {
                return Carbon::createFromFormat('n/j/y', (string) $fecha)->format('Y-m-d');
            } catch (\Exception $e) {
                return Carbon::now()->format('Y-m-d');
            }
        }

        return Carbon::now()->format('Y-m-d');
    }

    private function crearOperacion(array $afectacion, int $clienteId, int $usuarioDefaultId, int $numeroOperacion): ?Operacion
    {
        try {
            $fechaDeuda = $this->parsearFechaDeuda($afectacion['FECHA DEUDA'] ?? null);

            $cantCuotas = 1;
            if (!empty($afectacion['CUOTAS'])) {
                $cantCuotas = max(1, (int) $afectacion['CUOTAS']);
            }

            $valorCuota = 0;
            if (!empty($afectacion['IMPORTE'])) {
                $valorLimpioCuota = preg_replace('/[^\d.]/', '', str_replace(',', '.', (string) $afectacion['IMPORTE']));
                $valorCuota = (float) $valorLimpioCuota;
            }

            $deudaTotal = 0;
            if (!empty($afectacion['DEUDA TOTAL'])) {
                $valorLimpio = preg_replace('/[^\d.]/', '', str_replace(',', '', (string) $afectacion['DEUDA TOTAL']));
                $deudaTotal = (float) $valorLimpio;
            } elseif ($cantCuotas > 0 && $valorCuota > 0) {
                $deudaTotal = $cantCuotas * $valorCuota;
            }

            $nodoId = 1;
            if (!empty($afectacion['id_nodo'])) {
                $nodoExiste = \App\Models\Nodo::find((int) $afectacion['id_nodo']);
                if ($nodoExiste) {
                    $nodoId = (int) $afectacion['id_nodo'];
                }
            }

            $socioId = $this->resolverSocioIdDesdeAfectacion($afectacion);

            $tipoDeudor = strtoupper((string) ($afectacion['TIPO DEUDOR'] ?? ''));
            $tipo = 'Solicitante';
            if ($tipoDeudor === 'GARANTE') {
                $tipo = 'Garante';
            }

            $referencia = $this->normalizarReferencia($afectacion['REFERENCIA'] ?? null);

            $clase = mb_substr(trim((string) ($afectacion['OPERACION'] ?? '')), 0, 20);
            if ($clase === '') {
                $clase = 'Comercial';
            }

            $operacion = Operacion::create([
                'numero' => $numeroOperacion,
                'cliente_id' => $clienteId,
                'estado_actual' => mb_substr((string) ($afectacion['CODIGO DE ATRASO'] ?? 'PENDIENTE'), 0, 20),
                'fecha_estado' => $fechaDeuda,
                'nodo_id' => $nodoId,
                'socio_id' => $socioId,
                'tipo' => $tipo,
                'fecha_operacion' => $fechaDeuda,
                'valor_cuota' => $valorCuota,
                'cant_cuotas' => $cantCuotas,
                'total' => $deudaTotal,
                'fecha_cuota' => $fechaDeuda,
                'clase' => $clase,
                'referencia' => $referencia !== '' ? $referencia : null,
                'usuario_id' => $usuarioDefaultId,
            ]);

            return $operacion;
        } catch (\Exception $e) {
            Log::error('Error creando operacion: ' . $e->getMessage(), $afectacion);
            return null;
        }
    }

    private function obtenerReferenciasDuplicadasPorSocio(array $afectaciones): array
    {
        $registrosConReferenciaDuplicada = [];
        $referenciasPorSocio = [];
        $filasPorClave = [];

        foreach ($afectaciones as $indice => $afectacion) {
            $referencia = $this->normalizarReferencia($afectacion['REFERENCIA'] ?? null);
            if ($referencia === '') {
                continue;
            }

            $socioId = $this->resolverSocioIdDesdeAfectacion($afectacion);
            $clave = $socioId . '|' . $referencia;

            $referenciasPorSocio[$socioId][] = $referencia;
            $filasPorClave[$clave][] = [
                'indice' => $indice + 1,
                'id' => $afectacion['ID'] ?? 'N/A',
            ];
        }

        foreach ($referenciasPorSocio as $socioId => $referencias) {
            $referenciasUnicas = array_values(array_unique($referencias));
            if (empty($referenciasUnicas)) {
                continue;
            }

            // Normalizar referencias existentes en BD para evitar falsos negativos por formato.
            $referenciasBdNormalizadas = Operacion::where('socio_id', $socioId)
                ->whereNotNull('referencia')
                ->pluck('referencia')
                ->map(function ($ref) {
                    return $this->normalizarReferencia($ref);
                })
                ->filter(function ($ref) {
                    return $ref !== '';
                })
                ->unique()
                ->values()
                ->all();

            $referenciasExistentes = array_intersect($referenciasUnicas, $referenciasBdNormalizadas);

            foreach ($referenciasExistentes as $referenciaExistente) {
                $clave = $socioId . '|' . $referenciaExistente;
                $filas = $filasPorClave[$clave] ?? [];

                foreach ($filas as $fila) {
                    $registrosConReferenciaDuplicada[] = [
                        'indice' => $fila['indice'],
                        'id' => $fila['id'],
                        'socio_id' => $socioId,
                        'referencia' => $referenciaExistente,
                    ];
                }
            }
        }

        return $registrosConReferenciaDuplicada;
    }

    private function normalizarReferencia($referencia): string
    {
        $referenciaNormalizada = preg_replace('/[^A-Za-z0-9]/', '', (string) ($referencia ?? ''));
        return mb_substr($referenciaNormalizada, 0, 20);
    }

    private function resolverSocioIdDesdeAfectacion(array $afectacion): int
    {
        $socioId = 1;
        if (!empty($afectacion['id_socio'])) {
            $socioExiste = Socio::find((int) $afectacion['id_socio']);
            if ($socioExiste) {
                $socioId = (int) $afectacion['id_socio'];
            }
        }

        return $socioId;
    }
}
