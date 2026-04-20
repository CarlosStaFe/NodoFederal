<?php
namespace App\Http\Controllers;

use App\Models\Operacion;
use App\Models\Nodo;
use App\Models\Socio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;

class OperacionController extends Controller
{
    /**
     * Muestra el formulario de consulta de operaciones.
     */
    public function consultar()
    {
        $nodos = Nodo::orderBy('nombre')->get();
        $socios = Socio::orderBy('razon_social')->get();
        return view('admin.operaciones.consultar', compact('nodos', 'socios'));
    }
    
    /**
     * Obtiene los socios que pertenecen a un nodo específico
     */
    public function getSociosByNodo($nodoId)
    {
        $socios = Socio::where('nodo_id', $nodoId)->orderBy('razon_social')->get();
        return response()->json($socios);
    }
    
    /**
     * Calcula el CUIT a partir del DNI y sexo
     */
    private function calcularCuit($dni, $sexo)
    {
        // Pad el DNI a 8 dígitos
        $dni = str_pad($dni, 8, '0', STR_PAD_LEFT);
        
        // Prefijo según el sexo
        $prefijo = ($sexo === 'F') ? '27' : '20';
        if ($sexo === 'X') $prefijo = '23';
        
        $cuitBase = $prefijo . $dni;
        $multiplicadores = [5,4,3,2,7,6,5,4,3,2];
        
        $suma = 0;
        for($i = 0; $i < 10; $i++) {
            $suma += (int)$cuitBase[$i] * $multiplicadores[$i];
        }
        
        $resto = $suma % 11;
        $digito = 11 - $resto;
        
        if($digito === 11) $digito = 0;
        if($digito === 10) {
            // Cambiar prefijo y recalcular
            if($prefijo === '20') $prefijo = '23';
            else if($prefijo === '27') $prefijo = '23';
            
            $cuitBase = $prefijo . $dni;
            $suma = 0;
            for($i = 0; $i < 10; $i++) {
                $suma += (int)$cuitBase[$i] * $multiplicadores[$i];
            }
            $resto = $suma % 11;
            $digito = 11 - $resto;
            if($digito === 11) $digito = 0;
            if($digito === 10) $digito = 9;
        }
        
        return $prefijo . $dni . $digito;
    }

    /**
     * Obtiene un token de autenticación desde la API externa usando datos del .env
     */
    public function obtenerTokenApi()
    {
        $url = env('API_TOKEN');
        $user = env('API_USER');
        $password = env('API_PASSWORD');
        
        if (empty($url)) {
            return null;
        }
    
        $response = Http::post($url, [
            'username' => $user,
            'password' => $password,
        ]);

        if ($response && $response->successful()) {
            return $response->json();
        } else {
            return null;
        }
    }

    /**
     * Consulta la API externa por documento y muestra los datos en la vista.
     */
    public function consultarApiPorCuil(Request $request)
    {
        
        $dni = $request->input('documento');
        $cuit = $request->input('cuit');
        $tipo = $request->input('tipo');
        $sexo = $request->input('sexo');
        
        // Para consultas por DNI, debemos usar el CUIT calculado
        $cuilParaConsulta = null;
        
        if ($tipo === 'DNI' && $dni && $sexo) {
            // Calcular CUIT a partir del DNI y sexo
            $cuilParaConsulta = $this->calcularCuit($dni, $sexo);
        } elseif ($tipo === 'CUIT' && $cuit) {
            $cuilParaConsulta = $cuit;
        }
        
        if (!$cuilParaConsulta) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'No se pudo determinar el CUIT para la consulta.',
                    'data' => []
                ]);
            }
            return back()->with('error', 'No se pudo determinar el CUIT para la consulta.');
        }
        
        //$apiUrl = env('API_CUIL');
        if ($tipo === 'DNI') {
            $apiUrl = env('API_CUIL');
        } else {
            $apiUrl = env('API_CUIT');
        }

        // Reemplazar el placeholder con el CUIT
        $apiUrl = preg_replace('/\?/', $cuilParaConsulta, $apiUrl, 1);
        
        // Mostrar URL por consola para debug
        \Illuminate\Support\Facades\Log::info('API URL construida', [
            'tipo' => $tipo,
            'cuit_consulta' => $cuilParaConsulta,
            'api_url' => $apiUrl
        ]);

        // Obtener el token
        $token = $this->obtenerTokenApi();
        
        if (!$token) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'No se pudo obtener el token de autenticación.',
                    'data' => []
                ]);
            }
            return back()->with('error', 'No se pudo obtener el token de autenticación.');
        }
        $access_token = $token['access_token'] ?? null;

        $response = Http::withToken($access_token)->timeout(30)->get($apiUrl);

        if ($response->successful()) {
            $datos = $response->json();
            
            // Guardar en sesión para el informe
            session(['datos_api' => $datos]);
            // Persistir los datos en la sesión para el próximo request
                // Guardar en la tabla consultas si status 200
                $result = $datos['result'] ?? [];
                $idLog = $datos['data']['idLog'] ?? 0;
                $p = $datos['data']['datosParticulares'] ?? null;
                //dd($idLog, $result, $p);
                if ((isset($result['code']) && $result['code'] == 200) && (isset($result['info']) && $result['info'] === 'OK') && !empty($idLog)) {
                    $user = Auth::user();
                    
                    // Usar filtros del formulario si están seleccionados, sino usar valores del usuario
                    $nodoId = $request->input('nodo_id') ?: ($user->nodo_id ?? 24);
                    $socioId = $request->input('socio_id') ?: ($user->socio_id ?? 1);
                    
                    $cuitConsulta = $p['cuil'] ?? ($p['CUIL'] ?? '');
                    
                    // Verificar si ya existe una consulta reciente con los mismos datos (último minuto)
                    $existeReciente = \App\Models\Consulta::where('cuit', $cuitConsulta)
                        ->where('user_id', $user->id)
                        ->where('created_at', '>', now()->subMinute())
                        ->exists();
                    
                    if (!$existeReciente) {
                        //dd($datos);
                        \App\Models\Consulta::create([
                            'numero' => $idLog,
                            'tipo' => 'Consulta',
                            'cuit' => $cuitConsulta,
                            'apelynombres' => $p['apellidoNombre'] ?? ($p['nombre'] ?? 'SIN NOMBRE'),
                            'fecha' => now(),
                            'nodo_id' => $nodoId,
                            'socio_id' => $socioId,
                            'user_id' => $user->id,
                            // Puedes agregar más campos si el JSON tiene otros datos relevantes
                        ]);
                    }
                }
            //$datosBcra = $this->leerBCRA($cuit);
            //$datos['bcra'] = $datosBcra;
            //dd($datos);

            $request->session()->put('datos_api', $datos);
            
            // Si es una petición AJAX, devolver JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Consulta realizada exitosamente',
                    'redirect_url' => route('admin.operaciones.informe'),
                    'data' => [$datos] // Envolver en array para consistencia con el frontend
                ]);
            }
            
            // Redirigir al informe después de consultar (solo para peticiones normales)
            return redirect()->route('admin.operaciones.informe');
        } else {
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'No se pudo obtener datos de la API. Status: ' . $response->status(),
                    'debug' => $response->body(),
                    'data' => []
                ]);
            }
            return back()->with('error', 'No se pudo obtener datos de la API.');
        }
    }

    /**
     * Muestra los datos de la consulta en el formulario informe.
     */
    public function informe()
    {
        $datos = session('datos_api');
        // Si no hay datos en sesión, intentar recuperarlos del request anterior
        if (!$datos && request()->hasSession()) {
            $datos = request()->session()->get('datos_api');
        }
        
        // Si hay datos, verificar y actualizar tabla clientes
        if (isset($datos) && isset($datos['data'])) {
            $this->actualizarClientes($datos);
            
            // Buscar operaciones del cliente y agregarlas a $datos
            $p = $datos['data']['datosParticulares'] ?? null;
            if ($p && !empty($p['cuil'])) {
                $cuil = $p['cuil'];
                $cliente = \App\Models\Cliente::where('cuit', $cuil)->first();
                
                if ($cliente) {
                    $user = Auth::user();
                    
                    // Obtener operaciones donde es titular
                    $queryTitular = \App\Models\Operacion::where('cliente_id', $cliente->id)
                        ->where('tipo', 'Solicitante')
                        ->with(['socio', 'nodo']);
            
                    $operacionesTitular = $queryTitular->get();
                    
                    // Obtener operaciones donde es garante
                    $queryGarante = \App\Models\Operacion::where('cliente_id', $cliente->id)
                        ->where('tipo', 'Garante')
                        ->with(['socio', 'nodo']);
                    
                    $operacionesGarante = $queryGarante->get();
                    
                    // Agregar operaciones a $datos en formato JSON
                    $datos['operaciones'] = [
                        'cliente_id' => $cliente->id,
                        'cliente_cuit' => $cuil,
                        'como_titular' => $operacionesTitular->map(function($op) {
                            $opArray = $op->toArray();
                            $opArray['nodo_nombre'] = $op->nodo ? $op->nodo->nombre : null;
                            $opArray['socio_razon_social'] = $op->socio ? $op->socio->razon_social : null;
                            $opArray['socio_numero'] = $op->socio ? $op->socio->numero : null;
                            return $opArray;
                        })->toArray(),
                        'como_garante' => $operacionesGarante->map(function($op) {
                            $opArray = $op->toArray();
                            $opArray['nodo_nombre'] = $op->nodo ? $op->nodo->nombre : null;
                            $opArray['socio_razon_social'] = $op->socio ? $op->socio->razon_social : null;
                            $opArray['socio_numero'] = $op->socio ? $op->socio->numero : null;
                            return $opArray;
                        })->toArray(),
                        'total_como_titular' => $operacionesTitular->count(),
                        'total_como_garante' => $operacionesGarante->count(),
                        'nodos_involucrados' => $operacionesTitular->merge($operacionesGarante)->pluck('nodo.nombre')->unique()->values()->toArray(),
                        'socios_involucrados' => $operacionesTitular->merge($operacionesGarante)->map(function($op) {
                            return [
                                'id' => $op->socio->id ?? null,
                                'numero' => $op->socio->numero ?? null,
                                'razon_social' => $op->socio->razon_social ?? null
                            ];
                        })->unique('id')->values()->toArray(),
                        'resumen' => [
                            'activas_titular' => $operacionesTitular->where('estado_actual', 'ACTIVO')->count(),
                            'afectadas_titular' => $operacionesTitular->where('estado_actual', '!=', 'ACTIVO')->count(),
                            'activas_garante' => $operacionesGarante->where('estado_actual', 'ACTIVO')->count(),
                            'afectadas_garante' => $operacionesGarante->where('estado_actual', '!=', 'ACTIVO')->count(),
                        ]
                    ];
                }
            }
        }
        
        return view('admin.operaciones.informe', compact('datos'));
    }
    
    /**
     * Actualiza la tabla clientes con datos del informe si no existe el CUIL
     */
    private function actualizarClientes($datos)
    {
        $p = $datos['data']['datosParticulares'] ?? null;
        
        if (!$p || empty($p['cuil'])) {
            return; // No hay datos suficientes
        }
        
        $cuil = $p['cuil'];
        
        // Verificar si el cliente ya existe en la tabla
        $clienteExistente = \App\Models\Cliente::where('cuit', $cuil)->first();
        
        if (!$clienteExistente) {
            // El cliente no existe, crearlo con los datos del informe
            try {
                $cliente = new \App\Models\Cliente();
                
                // Datos básicos
                $tipoDoc = $p['tipo'] ?? 'DNI';
                // Simplificar tipo de documento si es muy largo
                if (strlen($tipoDoc) > 5) {
                    $tipoDoc = 'DNI'; // Valor por defecto si es muy largo
                }
                $cliente->tipodoc = $tipoDoc;
                $cliente->documento = $p['dni'] ?? '';
                $cliente->sexo = $p['sexo'] ?? 'M';
                $cliente->cuit = $cuil;
                $cliente->apelnombres = strtoupper($p['apellidoNombre'] ?? '');
                
                // Fecha de nacimiento
                if (isset($p['fechaNacimiento'])) {
                    try {
                        $cliente->nacimiento = \Carbon\Carbon::parse($p['fechaNacimiento'])->format('Y-m-d');
                    } catch (\Exception $e) {
                        $cliente->nacimiento = null;
                    }
                }
                
                // Datos de ubicación si están disponibles 
                $cliente->nacionalidad = $p['nacionalidad'] ?? '';
                $cliente->domicilio = $p['domicilio'] ?? '';
                
                // Buscar código postal por código postal si existe
                if (isset($p['cp']) && !empty($p['cp'])) {
                    $localidad = \App\Models\Localidad::where('cod_postal', $p['cp'])->first();
                    if ($localidad) {
                        $cliente->cod_postal_id = $localidad->id;
                    } else {
                        // Si no encuentra por código postal, intentar buscar por nombre de localidad
                        if (isset($p['localidad']) && !empty($p['localidad'])) {
                            $localidad = \App\Models\Localidad::where('localidad', 'LIKE', '%' . $p['localidad'] . '%')->first();
                            if ($localidad) {
                                $cliente->cod_postal_id = $localidad->id;
                            } else {
                                $cliente->cod_postal_id = 1; // Valor por defecto
                            }
                        } else {
                            $cliente->cod_postal_id = 1; // Valor por defecto
                        }
                    }
                } else if (isset($p['localidad']) && !empty($p['localidad'])) {
                    // Si no hay código postal, buscar por nombre de localidad
                    $localidad = \App\Models\Localidad::where('localidad', 'LIKE', '%' . $p['localidad'] . '%')->first();
                    if ($localidad) {
                        $cliente->cod_postal_id = $localidad->id;
                    } else {
                        $cliente->cod_postal_id = 1; // Valor por defecto
                    }
                } else {
                    $cliente->cod_postal_id = 1; // Valor por defecto
                }
                
                // Datos adicionales
                $cliente->telefono = '';
                $cliente->email = '';
                $cliente->estado = 'ACTIVO';
                $cliente->fechaestado = now();
                $cliente->observacion = 'Cliente creado automáticamente desde informe API';
                
                $cliente->save();
                
            } catch (\Exception $e) {
                // Error creando cliente desde informe
            }
        }
    }

    /**
     * Genera un PDF con los datos de la consulta.
     */
    public function pdf()
    {
        $datos = session('datos_api');
        if (!$datos && request()->hasSession()) {
            $datos = request()->session()->get('datos_api');
        }
        
        // Agregar datos de operaciones locales al PDF igual que en el informe
        if (isset($datos) && isset($datos['data'])) {
            $p = $datos['data']['datosParticulares'] ?? null;
            if ($p && !empty($p['cuil'])) {
                $cuil = $p['cuil'];
                $cliente = \App\Models\Cliente::where('cuit', $cuil)->first();
                
                if ($cliente) {
                    $user = Auth::user();
                    
                    // Obtener operaciones donde es titular
                    $queryTitular = \App\Models\Operacion::where('cliente_id', $cliente->id)
                        ->where('tipo', 'Solicitante')
                        ->with(['socio', 'nodo']);
            
                    $operacionesTitular = $queryTitular->get();
                    
                    // Obtener operaciones donde es garante
                    $queryGarante = \App\Models\Operacion::where('cliente_id', $cliente->id)
                        ->where('tipo', 'Garante')
                        ->with(['socio', 'nodo']);
                    
                    $operacionesGarante = $queryGarante->get();
                    
                    // Agregar operaciones a $datos en formato JSON
                    $datos['operaciones'] = [
                        'cliente_id' => $cliente->id,
                        'cliente_cuit' => $cuil,
                        'como_titular' => $operacionesTitular->map(function($op) {
                            $opArray = $op->toArray();
                            $opArray['nodo_nombre'] = $op->nodo ? $op->nodo->nombre : null;
                            $opArray['socio_razon_social'] = $op->socio ? $op->socio->razon_social : null;
                            $opArray['socio_numero'] = $op->socio ? $op->socio->numero : null;
                            return $opArray;
                        })->toArray(),
                        'como_garante' => $operacionesGarante->map(function($op) {
                            $opArray = $op->toArray();
                            $opArray['nodo_nombre'] = $op->nodo ? $op->nodo->nombre : null;
                            $opArray['socio_razon_social'] = $op->socio ? $op->socio->razon_social : null;
                            $opArray['socio_numero'] = $op->socio ? $op->socio->numero : null;
                            return $opArray;
                        })->toArray(),
                        'total_como_titular' => $operacionesTitular->count(),
                        'total_como_garante' => $operacionesGarante->count(),
                        'nodos_involucrados' => $operacionesTitular->merge($operacionesGarante)->pluck('nodo.nombre')->unique()->values()->toArray(),
                        'socios_involucrados' => $operacionesTitular->merge($operacionesGarante)->map(function($op) {
                            return [
                                'id' => $op->socio->id ?? null,
                                'numero' => $op->socio->numero ?? null,
                                'razon_social' => $op->socio->razon_social ?? null
                            ];
                        })->unique('id')->values()->toArray(),
                        'resumen' => [
                            'activas_titular' => $operacionesTitular->where('estado_actual', 'ACTIVO')->count(),
                            'afectadas_titular' => $operacionesTitular->where('estado_actual', '!=', 'ACTIVO')->count(),
                            'activas_garante' => $operacionesGarante->where('estado_actual', 'ACTIVO')->count(),
                            'afectadas_garante' => $operacionesGarante->where('estado_actual', '!=', 'ACTIVO')->count(),
                        ]
                    ];
                }
            }
        }
        
        $pdf = PDF::loadView('admin.operaciones.pdf', compact('datos'));
        return $pdf->stream();
    }

    /**
     * Carga la operación de un cliente.
     */
    public function cargar()
    {
        $user = Auth::user();
        $socio = null;
        
        // Si el usuario tiene un socio asignado, obtener sus datos
        if ($user && $user->socio_id) {
            $socio = \App\Models\Socio::find($user->socio_id);
        }
        
        return view('admin.operaciones.cargar', compact('socio'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar datos básicos
        $request->validate([
            'cuit' => 'required',
            'numero_socio' => 'required',
            'valor' => 'required|numeric',
            'cuotas' => 'required|integer',
            'total' => 'required|numeric',
            'vencimiento' => 'required|date',
            'operacion' => 'required',
        ]);

        // Buscar cliente y socio
        $cliente = \App\Models\Cliente::where('cuit', $request->cuit)->firstOrFail();
        $socio = \App\Models\Socio::where('numero', $request->numero_socio)->firstOrFail();
        $user = Auth::user();

        // Crear operación
        $operacion = new Operacion();
        // Buscar el primer número libre desde 1
        $numero = 1;
        while (Operacion::where('numero', $numero)->exists()) {
            $numero++;
        }
        $operacion->numero = $numero;
        $operacion->cliente_id = $cliente->id;
        $operacion->estado_actual = $cliente->estado;
        $operacion->fecha_estado = $cliente->fechaestado ?? now();
        $operacion->nodo_id = $socio->nodo_id;
        $operacion->socio_id = $socio->id;
        $operacion->tipo = "Solicitante";
        $operacion->fecha_operacion = now();
        $operacion->valor_cuota = $request->valor;
        $operacion->cant_cuotas = $request->cuotas;
        $operacion->total = $request->total;
        $operacion->fecha_cuota = $request->vencimiento;
        $operacion->clase = $request->operacion;
        $operacion->usuario_id = $user->id;

        $operacion->save();

        // Guardar garantes si existen en el request
        if ($request->filled('garantes_json')) {
            $garantes = json_decode($request->garantes_json, true);
            //dd($garantes);
            if (is_array($garantes)) {
                foreach ($garantes as $garante) {
                    // Crear operación tipo "Garante" para el garante
                    Operacion::create([
                        'numero' => $numero, // puedes ajustar si necesitas otro número
                        'cliente_id' => $garante['id'] ?? null,
                        'estado_actual' => 'ACTIVO',
                        'fecha_estado' => now(),
                        'nodo_id' => $operacion->nodo_id,
                        'socio_id' => $operacion->socio_id,
                        'tipo' => 'Garante',
                        'fecha_operacion' => now(),
                        'valor_cuota' => $operacion->valor_cuota,
                        'cant_cuotas' => $operacion->cant_cuotas,
                        'total' => $operacion->total,
                        'fecha_cuota' => $operacion->fecha_cuota,
                        'clase' => $operacion->clase,
                        'usuario_id' => $operacion->usuario_id,
                    ]);
                }
            }
        }

        return redirect()->route('admin.operaciones.cargar', ['id' => $operacion->id])
            ->with('mensaje', "Operación registrada ->>> NÚMERO: " . $numero)
            ->with('icono', 'success')
            ->with('showConfirmButton', true)
            ->with('timer', 100000);
    }

    /**
    * Carga la operación de un cliente.
    */
    public function show(Request $request)
    {
        $cuit = $request->input('cuit');
        $cliente = null;
        $operaciones = collect();
        $operacionesComoGarante = collect();
        $user = Auth::user();
        if ($cuit) {
            $cliente = \App\Models\Cliente::where('cuit', $cuit)->first();
            if ($cliente) {
                // Operaciones donde es titular
                $query = Operacion::where('cliente_id', $cliente->id)->where('tipo', 'Solicitante');
                if ($user->role == 'socio') {
                    // Solo operaciones del socio
                    $query->where('socio_id', $user->socio_id);
                } elseif ($user->role == 'nodo') {
                    // Solo operaciones de socios de su nodo
                    $query->whereHas('socio', function($q) use ($user) {
                        $q->where('nodo_id', $user->nodo_id);
                    });
                }
                $operaciones = $query->get();

                // Operaciones donde es garante
                $comoGarante = Operacion::where('cliente_id', $cliente->id)->where('tipo', 'Garante');
                if ($user->role == 'socio') {
                    // Solo operaciones del socio
                    $comoGarante->where('socio_id', $user->socio_id);
                } elseif ($user->role == 'nodo') {
                    // Solo operaciones de socios de su nodo
                    $comoGarante->whereHas('socio', function($q) use ($user) {
                        $q->where('nodo_id', $user->nodo_id);
                    });
                }
                $operacionesComoGarante = $comoGarante->get();
            }
        }
        return view('admin.operaciones.show', compact('cliente', 'operaciones', 'operacionesComoGarante', 'cuit'));
    }

    /**
    * Muestra el formulario para afectar una operación.
    */
    public function afectar($id)
    {
        $operacion = Operacion::findOrFail($id);
        $cliente = $operacion->cliente;
        $socio = $operacion->socio;
        //dd($operacion, $cliente, $socio);
        // Puedes agregar más datos si lo necesitas
        return view('admin.operaciones.afectar', compact('operacion', 'cliente', 'socio'));
    }

    /**
    * Procesa la afectación de una operación.
    */
    public function afectarStore(Request $request, $id)
    {
        $operacion = Operacion::findOrFail($id);
        $fechaAfectacion = $request->input('fecha_afectacion') ?? now();
        $nuevoEstado = $request->input('estado_nuevo');
        
        // Actualizar operación
        $operacion->fecha_estado = $fechaAfectacion;
        $operacion->estado_actual = $nuevoEstado;
        $operacion->save();

        // Actualizar garantes (tipo 'Garante')
        $garantes = Operacion::where('numero', $operacion->numero)
            ->where('tipo', 'Garante')
            ->get();
        foreach ($garantes as $garante) {
            $garante->fecha_estado = $fechaAfectacion;
            $garante->estado_actual = $nuevoEstado;
            $garante->save();
            // Actualizar estado en la tabla clientes para el garante
            $clienteG = $garante->cliente;
            if ($clienteG) {
                $clienteG->estado = $nuevoEstado;
                $clienteG->fechaestado = $fechaAfectacion;
                $clienteG->save();
            }
        }

        // Actualizar estado en el cliente relacionado
        $cliente = $operacion->cliente;
        if ($cliente) {
            $cliente->estado = $nuevoEstado;
            $cliente->fechaestado = $fechaAfectacion;
            $cliente->save();
        }

        return redirect()->route('admin.operaciones.show', ['id' => $operacion->id])
            ->with('mensaje', 'Operación, garantes y cliente afectados correctamente.')
            ->with('icono', 'success')
            ->with('showConfirmButton', false);
    }

    /**
     * Consulta las APIs de BCRA usando el CUIT y retorna los resultados combinados.
     */
    public function leerBCRA($cuit)
    {
        if (!$cuit) {
            // Intentar obtener el cuil de la sesión de la última consulta
            $datos = session('datos_api');
            $cuit = $datos['data']['datosParticulares']['cuil'] ?? $datos['data']['datosParticulares']['CUIL'] ?? null;
        }
        if (!$cuit) return null;

        $resultados = [];
        $apis = [
            'deudas' => env('API_DEUDAS_BCRA'),
            'cheques' => env('API_CHEQUES_BCRA'),
            'historico' => env('API_HISTORICO_BCRA'),
        ];
        foreach ($apis as $key => $url) {
            if ($url) {
                $apiUrl = preg_replace('/\?/', $cuit, $url, 1);
                $response = Http::withoutVerifying()->get($apiUrl);
                $resultados[$key] = $response->successful() ? $response->json() : null;
            } else {
                $resultados[$key] = null;
            }
        }
        //dd($resultados);
        return $resultados;
    }

    /**
     * Muestra el listado de operaciones con filtros
     */
    public function listar(Request $request)
    {
        $user = Auth::user();
        $roles = $user->roles->pluck('name');
        
        // Obtener nodos según permisos
        if ($roles->contains('nodo')) {
            // Si es nodo, solo ve su nodo
            $nodos = Nodo::where('id', $user->nodo_id)->get();
        } elseif ($roles->contains('socio')) {
            // Si es socio, ve el nodo de su socio
            $socio = Socio::find($user->socio_id);
            $nodos = $socio ? Nodo::where('id', $socio->nodo_id)->get() : collect();
        } else {
            // Admin y secretaria ven todos los nodos
            $nodos = Nodo::orderBy('nombre')->get();
        }
        
        // Obtener socios según permisos
        if ($roles->contains('nodo')) {
            // Si es nodo, solo ve sus socios
            $socios = Socio::where('nodo_id', $user->nodo_id)->orderBy('razon_social')->get();
        } elseif ($roles->contains('socio')) {
            // Si es socio, solo ve su propio socio
            $socios = Socio::where('id', $user->socio_id)->get();
        } else {
            // Admin y secretaria ven todos los socios
            $socios = Socio::orderBy('razon_social')->get();
        }

        // Si es una petición AJAX, procesar filtros y devolver operaciones
        if ($request->ajax()) {
            try {
                $query = Operacion::with(['cliente', 'nodo', 'socio', 'usuario']);

                // Aplicar filtros según el rol del usuario
                if ($roles->contains('nodo')) {
                    $query->where('nodo_id', $user->nodo_id);
                } elseif ($roles->contains('socio')) {
                    $query->where('socio_id', $user->socio_id);
                }

                // Aplicar filtros del formulario
                $nodo_id = $request->input('nodo_id');
                if (!empty($nodo_id)) {
                    // Verificar permisos antes de aplicar filtro
                    if ($roles->contains('nodo') && $nodo_id != $user->nodo_id) {
                        return response()->json(['error' => 'Sin permisos para ver este nodo'], 403);
                    }
                    if ($roles->contains('socio')) {
                        $socio = Socio::find($user->socio_id);
                        if ($socio && $nodo_id != $socio->nodo_id) {
                            return response()->json(['error' => 'Sin permisos para ver este nodo'], 403);
                        }
                    }
                    $query->where('nodo_id', $nodo_id);
                }

                $socio_id = $request->input('socio_id');
                if (!empty($socio_id)) {
                    // Verificar permisos antes de aplicar filtro
                    if ($roles->contains('nodo')) {
                        $socio = Socio::find($socio_id);
                        if (!$socio || $socio->nodo_id != $user->nodo_id) {
                            return response()->json(['error' => 'Sin permisos para ver este socio'], 403);
                        }
                    }
                    if ($roles->contains('socio') && $socio_id != $user->socio_id) {
                        return response()->json(['error' => 'Sin permisos para ver este socio'], 403);
                    }
                    $query->where('socio_id', $socio_id);
                }

                $desde_fecha = $request->input('desde_fecha');
                $hasta_fecha = $request->input('hasta_fecha');
                
                if ($desde_fecha && $hasta_fecha) {
                    $query->whereBetween('fecha_operacion', [
                        $desde_fecha . ' 00:00:00', 
                        $hasta_fecha . ' 23:59:59'
                    ]);
                }

                // Aplicar búsqueda de DataTables
                $searchValue = $request->input('search.value');
                if (!empty($searchValue)) {
                    $query->where(function($q) use ($searchValue) {
                        $q->where('numero', 'LIKE', "%{$searchValue}%")
                          ->orWhere('estado_actual', 'LIKE', "%{$searchValue}%")
                          ->orWhere('tipo', 'LIKE', "%{$searchValue}%")
                          ->orWhereHas('cliente', function($clienteQuery) use ($searchValue) {
                              $clienteQuery->where('cuit', 'LIKE', "%{$searchValue}%")
                                          ->orWhere('apelnombres', 'LIKE', "%{$searchValue}%");
                          })
                          ->orWhereHas('nodo', function($nodoQuery) use ($searchValue) {
                              $nodoQuery->where('nombre', 'LIKE', "%{$searchValue}%");
                          })
                          ->orWhereHas('socio', function($socioQuery) use ($searchValue) {
                              $socioQuery->where('razon_social', 'LIKE', "%{$searchValue}%");
                          });
                    });
                }

                // Obtener resultados con paginación para DataTables
                $start = (int) $request->input('start', 0);
                $length = (int) $request->input('length', 50);
                $draw = (int) $request->input('draw', 1);

                // Contar total sin filtros
                $totalQuery = Operacion::query();
                if ($roles->contains('nodo')) {
                    $totalQuery->where('nodo_id', $user->nodo_id);
                } elseif ($roles->contains('socio')) {
                    $totalQuery->where('socio_id', $user->socio_id);
                }
                $recordsTotal = $totalQuery->count();

                // Aplicar filtros y contar
                $recordsFiltered = $query->count();

                // Obtener datos paginados
                $operaciones = $query->orderBy('fecha_operacion', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->skip($start)
                    ->take($length)
                    ->get();

                // Formatear datos para DataTables
                $data = [];
                foreach ($operaciones as $operacion) {
                    $fechaOp = $operacion->fecha_operacion ? \Carbon\Carbon::parse($operacion->fecha_operacion)->format('d/m/Y') : '';
                    $fechaEstado = $operacion->fecha_estado ? \Carbon\Carbon::parse($operacion->fecha_estado)->format('d/m/Y') : '';
                    $valorCuota = $operacion->valor_cuota ? '$' . number_format($operacion->valor_cuota, 2, ',', '.') : '';
                    $valorTotal = $operacion->total ? '$' . number_format($operacion->total, 2, ',', '.') : '';
                    
                    $estadoClass = $operacion->estado_actual === 'ACTIVO' ? 'text-success' : 
                                  ($operacion->estado_actual === 'AFECTADO' ? 'text-danger' : 'text-warning');

                    $acciones = '';
                    if (auth()->user()->can('admin.operaciones.show')) {
                        $cuit = $operacion->cliente ? $operacion->cliente->cuit : '';
                        $acciones .= '<a href="/admin/operaciones/show?cuit=' . $cuit . '" class="btn btn-primary btn-sm me-1" title="Ver operaciones"><i class="bi bi-eye"></i></a>';
                    }
                    if (auth()->user()->can('admin.operaciones.cargar')) {
                        $acciones .= '<a href="/admin/operaciones/afectar/' . $operacion->id . '" class="btn btn-warning btn-sm" title="Afectar/Desafectar"><i class="bi bi-fire"></i></a>';
                    }

                    $data[] = [
                        'numero' => '<strong>' . ($operacion->numero ?? '') . '</strong>',
                        'cuit' => $operacion->cliente ? $operacion->cliente->cuit : '',
                        'apellidos' => $operacion->cliente ? $operacion->cliente->apelnombres : '',
                        'estado' => '<span class="' . $estadoClass . '"><strong>' . ($operacion->estado_actual ?? '') . '</strong></span>',
                        'tipo' => '<span class="badge ' . ($operacion->tipo === 'Solicitante' ? 'badge-primary' : 'badge-secondary') . '">' . ($operacion->tipo ?? '') . '</span>',
                        'fecha_operacion' => $fechaOp,
                        'fecha_estado' => $fechaEstado,
                        'nodo' => $operacion->nodo ? $operacion->nodo->nombre : '',
                        'socio' => $operacion->socio ? $operacion->socio->razon_social : '',
                        'total' => '<div class="text-end">' . $valorTotal . '</div>',
                        'acciones' => '<div class="text-center">' . $acciones . '</div>'
                    ];
                }

                return response()->json([
                    'draw' => $draw,
                    'recordsTotal' => $recordsTotal,
                    'recordsFiltered' => $recordsFiltered,
                    'data' => $data
                ]);
                
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Error interno del servidor',
                    'message' => $e->getMessage(),
                    'operaciones' => []
                ], 500);
            }
        }

        // Para peticiones normales, devolver la vista
        return view('admin.operaciones.listar', compact('nodos', 'socios'));
    }

    /**
     * Debug de operaciones (temporal)
     */
    public function debug()
    {
        $user = Auth::user();
        $roles = $user->roles->pluck('name');
        
        // Obtener nodos según permisos
        if ($roles->contains('nodo')) {
            $nodos = Nodo::where('id', $user->nodo_id)->get();
        } elseif ($roles->contains('socio')) {
            $socio = Socio::find($user->socio_id);
            $nodos = $socio ? Nodo::where('id', $socio->nodo_id)->get() : collect();
        } else {
            $nodos = Nodo::orderBy('nombre')->get();
        }
        
        // Obtener socios según permisos
        if ($roles->contains('nodo')) {
            $socios = Socio::where('nodo_id', $user->nodo_id)->orderBy('razon_social')->get();
        } elseif ($roles->contains('socio')) {
            $socios = Socio::where('id', $user->socio_id)->get();
        } else {
            $socios = Socio::orderBy('razon_social')->get();
        }

        return view('admin.operaciones.debug', compact('nodos', 'socios'));
    }

    /**
     * Obtener socios por nodo para el listado de operaciones
     */
    public function getSociosByNodoForList($nodoId)
    {
        $user = Auth::user();
        $roles = $user->roles->pluck('name');
        
        // Verificar permisos
        if ($roles->contains('nodo') && $nodoId != $user->nodo_id) {
            return response()->json(['error' => 'Sin permisos'], 403);
        }
        
        if ($roles->contains('socio')) {
            $socio = Socio::find($user->socio_id);
            if (!$socio || $socio->nodo_id != $nodoId) {
                return response()->json(['error' => 'Sin permisos'], 403);
            }
        }
        
        $socios = Socio::where('nodo_id', $nodoId)->orderBy('razon_social')->get();
        return response()->json($socios);
    }

}
