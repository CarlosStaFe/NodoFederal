<?php

namespace App\Http\Controllers;

use App\Models\Operacion;
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

    public function informe()
    {
        $datos = session('datos_api');
        // Si no hay datos en sesión, intentar recuperarlos del request anterior
        if (!$datos && request()->hasSession()) {
            $datos = request()->session()->get('datos_api');
        }
        return view('admin.operaciones.informe', compact('datos'));
    }

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
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Operacion $operacion)
    {
        //
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
