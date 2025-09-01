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
    public function consultarApiPorDocumento(Request $request)
    {
        $dni = $request->input('documento');
        $apiUrl = env('API_DOC');
        $apiUrl = preg_replace('/=(\?)/', '='.$dni, $apiUrl, 1);

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
        $operacion = new \App\Models\Operacion();
        $operacion->numero = rand(100000, 999999); // O usa lógica adecuada
        $operacion->id_cliente = $cliente->id;
        $operacion->estado_actual = $cliente->estado;
        $operacion->fecha_estado = $cliente->fechaestado ?? now();
        $operacion->id_socio = $socio->id;
        $operacion->tipo = 'Solicitante';
        $operacion->fecha_operacion = now();
        $operacion->valor_cuota = $request->valor;
        $operacion->cant_cuotas = $request->cuotas;
        $operacion->total = $request->total;
        $operacion->fecha_cuota = $request->vencimiento;
        $operacion->clase = $request->operacion;
        $operacion->id_usuario = $user->id;
        $operacion->save();

        // Guardar garantes si existen
        if ($request->filled('garantes_json')) {
            $garantes = json_decode($request->garantes_json, true);
            if (is_array($garantes)) {
                foreach ($garantes as $garante) {
                    \App\Models\Garante::create([
                        'operacion_id' => $operacion->id,
                        'cuit' => $garante['cuit'] ?? '',
                        'tipodoc' => $garante['tipodoc'] ?? '',
                        'sexo' => $garante['sexo'] ?? '',
                        'documento' => $garante['documento'] ?? '',
                        'apelnombres' => $garante['apelnombres'] ?? '',
                    ]);
                }
            }
        }

        return redirect()->route('admin.operaciones.show', ['id' => $operacion->id])
            ->with('mensaje', 'Operación registrada correctamente.')
            ->with('icono', 'success');
    }

    /**
    * Carga la operación de un cliente.
    */
    public function show()
    {
        return view('admin.operaciones.show');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Operacion $operacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Operacion $operacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Operacion $operacion)
    {
        //
    }
}
