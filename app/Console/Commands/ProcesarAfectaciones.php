<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Models\Operacion;
use App\Models\Localidad;
use App\Models\Nodo;
use App\Models\Socio;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ProcesarAfectaciones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'procesar:afectaciones {--test=0 : Número de registros de prueba (0 = todos)}';

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
        $this->info('Iniciando procesamiento de afectaciones...');
        
        // Verificar si el archivo existe usando ruta completa
        $rutaArchivo = storage_path('app/afectaciones.json');
        if (!file_exists($rutaArchivo)) {
            $this->error("El archivo afectaciones.json no existe en: {$rutaArchivo}");
            return 1;
        }

        // Leer el archivo JSON
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

        $this->info('Total de registros a procesar: ' . count($afectaciones));
        
        // Obtener una localidad y usuario por defecto para campos obligatorios
        $localidadDefault = Localidad::first();
        $usuarioDefault = User::first();
        
        if (!$localidadDefault || !$usuarioDefault) {
            $this->error('No se encontraron localidades o usuarios en la base de datos');
            return 1;
        }

        $clientesCreados = 0;
        $operacionesCreadas = 0;
        $errores = 0;

        $progressBar = $this->output->createProgressBar(count($afectaciones));
        $progressBar->start();

        foreach ($afectaciones as $afectacion) {
            try {
                // Buscar cliente por CUIL
                $cliente = Cliente::where('cuit', $afectacion['CUIL'] ?? '')->first();
                
                // Si no existe el cliente, crearlo
                if (!$cliente) {
                    $cliente = $this->crearCliente($afectacion, $localidadDefault->id);
                    if ($cliente) {
                        $clientesCreados++;
                    }
                }

                // Crear operación si se tiene cliente
                if ($cliente) {
                    $operacion = $this->crearOperacion($afectacion, $cliente->id, $usuarioDefault->id);
                    if ($operacion) {
                        $operacionesCreadas++;
                    }
                }

            } catch (\Exception $e) {
                $errores++;
                \Log::error('Error procesando afectación ID: ' . ($afectacion['ID'] ?? 'N/A'), [
                    'error' => $e->getMessage(),
                    'data' => $afectacion
                ]);
            }
            
            $progressBar->advance();
        }

        $progressBar->finish();
        
        $this->newLine();
        $this->info("Procesamiento completado:");
        $this->info("- Clientes creados: {$clientesCreados}");
        $this->info("- Operaciones creadas: {$operacionesCreadas}");
        if ($errores > 0) {
            $this->warn("- Errores: {$errores}");
        }

        return 0;
    }

    private function crearCliente($afectacion, $localidadDefaultId)
    {
        try {
            // Extraer DNI del CUIL (quitar primeros 2 y último dígito)
            $cuil = $afectacion['CUIL'] ?? '';
            $documento = $afectacion['DNI'] ?? '';
            
            // Si no hay DNI, intentar extraerlo del CUIL
            if (empty($documento) && strlen($cuil) == 11) {
                $documento = substr($cuil, 2, -1);
            }
            
            // Asegurar que el documento no exceda el rango de integer (máximo 2,147,483,647)
            $documentoInt = 0;
            if (!empty($documento)) {
                $documentoInt = (int)$documento;
                if ($documentoInt > 2147483647) {
                    $documentoInt = 0; // Si es muy grande, usar 0
                }
            }

            $cliente = Cliente::create([
                'tipodoc' => 'DNI',
                'documento' => $documentoInt,
                'sexo' => '-',
                'cuit' => $cuil ?: '-',
                'apelnombres' => mb_substr($afectacion['TITULAR'] ?? '-', 0, 50), // Limitar a 50 caracteres
                'nacimiento' => null,
                'nacionalidad' => "Argentina",
                'domicilio' => '-',
                'cod_postal_id' => $localidadDefaultId,
                'telefono' => '-',
                'email' => null,
                'estado' => 'Activo',
                'fechaestado' => null,
                'observacion' => 'Importado desde afectaciones.json'
            ]);

            return $cliente;
        } catch (\Exception $e) {
            \Log::error('Error creando cliente: ' . $e->getMessage(), $afectacion);
            return null;
        }
    }

    private function crearOperacion($afectacion, $clienteId, $usuarioDefaultId)
    {
        try {
            // Procesar fecha de deuda
            $fechaDeuda = null;
            if (!empty($afectacion['FECHA DEUDA'])) {
                try {
                    // Parsear fecha desde formato "m/d/y" (ej: "7/16/21") y convertir a "yyyy-mm-dd"
                    $fechaCarbon = Carbon::createFromFormat('n/j/y', $afectacion['FECHA DEUDA']);
                    $fechaDeuda = $fechaCarbon->format('Y-m-d');
                } catch (\Exception $e) {
                    $fechaDeuda = Carbon::now()->format('Y-m-d');
                }
            } else {
                $fechaDeuda = Carbon::now()->format('Y-m-d');
            }

            // Procesar deuda total (quitar símbolos y convertir a decimal)
            $deudaTotal = 0;
            if (!empty($afectacion['DEUDA TOTAL'])) {
                // Eliminar comas y otros símbolos, luego convertir a float y formatear con 2 decimales
                $valorLimpio = preg_replace('/[^\d.]/', '', str_replace(',', '', $afectacion['DEUDA TOTAL']));
                $deudaTotal = number_format((float)$valorLimpio, 2, '.', '');
            }

            // Verificar que nodo_id existe, sino usar 1 por defecto
            $nodoId = 1;
            if (!empty($afectacion['id_nodo'])) {
                $nodoExiste = \App\Models\Nodo::find((int)$afectacion['id_nodo']);
                if ($nodoExiste) {
                    $nodoId = (int)$afectacion['id_nodo'];
                }
            }

            // Verificar que socio_id existe, sino usar 1 por defecto
            $socioId = 1;
            if (!empty($afectacion['id_socio'])) {
                $socioExiste = \App\Models\Socio::find((int)$afectacion['id_socio']);
                if ($socioExiste) {
                    $socioId = (int)$afectacion['id_socio'];
                }
            }

            // Procesar tipo de deudor
            $tipoDeudor = strtoupper($afectacion['TIPO DEUDOR'] ?? '');
            $tipo = 'Solicitante'; // Valor por defecto
            if ($tipoDeudor === 'SOLICITANTE') {
                $tipo = 'Solicitante';
            } elseif ($tipoDeudor === 'GARANTE') {
                $tipo = 'Garante';
            }

            $operacion = Operacion::create([
                'numero' => (int)($afectacion['ID'] ?? 0),
                'cliente_id' => $clienteId,
                'estado_actual' => mb_substr($afectacion['CODIGO DE ATRASO'] ?? 'PENDIENTE', 0, 20),
                'fecha_estado' => $fechaDeuda,
                'nodo_id' => $nodoId,
                'socio_id' => $socioId,
                'tipo' => $tipo,
                'fecha_operacion' => $fechaDeuda,
                'valor_cuota' => 0,
                'cant_cuotas' => 1,
                'total' => $deudaTotal,
                'fecha_cuota' => $fechaDeuda,
                'clase' => 'Comercial',
                'usuario_id' => $usuarioDefaultId
            ]);

            return $operacion;
        } catch (\Exception $e) {
            \Log::error('Error creando operación: ' . $e->getMessage(), $afectacion);
            return null;
        }
    }
}
