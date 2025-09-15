<?php
namespace App\Http\Controllers;

use App\Models\Operacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class OperacionController extends Controller
{
    /**
     * Muestra el formulario de consulta de operaciones.
     */
    public function consultar()
    {
        return view('admin.operaciones.consultar');
    }
    
    /**
     * Obtiene un token de autenticación desde la API externa usando datos del .env
     */
    public function obtenerTokenApi()
    {
        $url = env('API_TOKEN');
        $user = env('API_USER');
        $password = env('API_PASSWORD');
        //dd($url, $user, $password);
        
        if (empty($url)) {
            Log::error('API_TOKEN no está definido en el .env');
            return null;
        }
    
        $response = Http::post($url, [
            'username' => $user,
            'password' => $password,
        ]);
    
        // dd($response->json());

        if ($response && $response->successful()) {
            return $response->json();
        } else {
            Log::error('Error autenticando con la API', [
                'url' => $url,
                'status' => $response ? $response->status() : 'sin respuesta',
                'body' => $response ? $response->body() : 'sin respuesta'
            ]);
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
        $apiUrl = env('API_CUIL');

        if ($cuit) {
            $apiUrl = preg_replace('/\?/', $cuit, $apiUrl, 1);
        } elseif ($dni) {
            $apiUrl = preg_replace('/\?/', $dni, $apiUrl, 1);
        }
        //dd($apiUrl);

        // Obtener el token
        $token = $this->obtenerTokenApi();
        //dd($token);
        if (!$token) {
            return back()->with('error', 'No se pudo obtener el token de autenticación.');
        }
        $access_token = $token['access_token'] ?? null;
        //dd($access_token);

        if (!$token) {
            return back()->with('error', 'No se pudo obtener el token de autenticación.');
        }

        $response = Http::withToken($access_token)->get($apiUrl);
        // Mostrar en consola para depuración
        //dd($response);

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
                if ((isset($result['code']) && $result['code'] == 200) && (isset($result['info']) && $result['info'] === 'OK')) {
                    $user = Auth::user();
                    //dd($datos);
                    \App\Models\Consulta::create([
                        'numero' => $idLog,
                        'tipo' => 'Consulta',
                        'cuit' => $p['cuil'] ?? ($p['CUIL'] ?? ''),
                        'apelynombres' => $p['apellidoNombre'] ?? ($p['nombre'] ?? 'SIN NOMBRE'),
                        'fecha' => now(),
                        'nodo_id' => $user->nodo_id ?? 24,
                        'socio_id' => $user->socio_id ?? 1,
                        'user_id' => $user->id,
                        // Puedes agregar más campos si el JSON tiene otros datos relevantes
                    ]);
                }
            //$datosBcra = $this->leerBCRA($cuit);
            //$datos['bcra'] = $datosBcra;
            //dd($datos);

            $request->session()->put('datos_api', $datos);
            // Redirigir al informe después de consultar
            return redirect()->route('admin.operaciones.informe');
        } else {
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
        return view('admin.operaciones.informe', compact('datos'));
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
        $pdf = PDF::loadView('admin.operaciones.pdf', compact('datos'));
        return $pdf->stream();
    }

    /**
     * Carga la operación de un cliente.
     */
    public function cargar()
    {
        return view('admin.operaciones.cargar');
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

}
