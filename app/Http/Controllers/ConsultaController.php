<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ConsultaController extends Controller
{
    public function consultar(Request $request)
    {
        $user = Auth::user();
        $roles = $user->roles->pluck('name');
        
        if ($roles->contains('nodo')) {
            $nodos = \App\Models\Nodo::where('id', $user->nodo_id)->get();
            $socios = \App\Models\Socio::where('nodo_id', $user->nodo_id)->get();
        } elseif ($roles->contains('admin') || $roles->contains('secretaria')) {
            $nodos = \App\Models\Nodo::all();
            $socios = \App\Models\Socio::all();
        } else {
            $nodos = collect();
            $socios = collect();
        }

        // Si es una petición AJAX, procesar filtros y devolver JSON
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $consulta = \App\Models\Consulta::query();
                
                // Filtrar por nodo si se especifica
                $nodo_id = $request->input('nodo_id');
                if (!empty($nodo_id)) {
                    $consulta->where('nodo_id', $nodo_id);
                }
                
                // Filtrar por socio si se especifica
                $socio_id = $request->input('socio_id');
                if (!empty($socio_id)) {
                    $consulta->where('socio_id', $socio_id);
                }
                
                // Filtrar por fechas (ambas requeridas)
                $desde_fecha = $request->input('desde_fecha');
                $hasta_fecha = $request->input('hasta_fecha');
                
                if ($desde_fecha && $hasta_fecha) {
                    // Asegurar formato yyyy-mm-dd para las fechas
                    $desde_fecha_formateada = \Carbon\Carbon::parse($desde_fecha)->format('Y-m-d');
                    $hasta_fecha_formateada = \Carbon\Carbon::parse($hasta_fecha)->format('Y-m-d');
                    
                    $consulta->whereBetween('fecha', [
                        $desde_fecha_formateada . ' 00:00:00', 
                        $hasta_fecha_formateada . ' 23:59:59'
                    ]);
                }
                
                // Si el usuario tiene rol nodo, filtrar solo sus consultas
                if ($roles->contains('nodo')) {
                    $consulta->where('nodo_id', $user->nodo_id);
                }
                
                // Cargar relaciones y obtener resultados ordenados por nodo, socio, fecha y hora ascendente
                $resultados = $consulta->with(['nodo', 'socio'])
                    ->orderByRaw('DATE(fecha) ASC, TIME(fecha) ASC')
                    ->get()
                    ->sortBy([
                        ['nodo.nombre', 'asc'],
                        ['socio.razon_social', 'asc'],
                        ['fecha', 'asc']
                    ])
                    ->values();
                
                // Debug: registrar la consulta SQL y parámetros para verificar ordenamiento
                Log::info('Consulta ejecutada con ordenamiento por nodo, socio, fecha y hora');
                Log::info('Parámetros de consulta: ', $request->all());
                Log::info('Cantidad de resultados: ' . $resultados->count());
                if ($resultados->count() > 0) {
                    $primero = $resultados->first();
                    $ultimo = $resultados->last();
                    Log::info('Primer resultado - Nodo: ' . ($primero->nodo ? $primero->nodo->nombre : 'N/A') . ' - Socio: ' . ($primero->socio ? $primero->socio->razon_social : 'N/A') . ' - Fecha: ' . $primero->fecha);
                    Log::info('Último resultado - Nodo: ' . ($ultimo->nodo ? $ultimo->nodo->nombre : 'N/A') . ' - Socio: ' . ($ultimo->socio ? $ultimo->socio->razon_social : 'N/A') . ' - Fecha: ' . $ultimo->fecha);
                }
                
                return response()->json([
                    'consultas' => $resultados
                ]);
                
            } catch (\Exception $e) {
                Log::error('Error en consulta AJAX: ' . $e->getMessage());
                return response()->json([
                    'error' => 'Error interno del servidor',
                    'message' => $e->getMessage(),
                    'consultas' => []
                ], 500);
            }
        }

        // Para peticiones normales (carga inicial), devolver la vista
        $consulta = collect(); // Inicialmente vacío
        
        return view('admin.administracion.consultar', compact('nodos', 'socios', 'consulta'));
    }

    public function generarPdf(Request $request)
    {
        $user = Auth::user();
        $roles = $user->roles->pluck('name');
        
        try {
            $consulta = \App\Models\Consulta::query();
            
            // Aplicar los mismos filtros que en la consulta AJAX
            $nodo_id = $request->input('nodo_id');
            if (!empty($nodo_id)) {
                $consulta->where('nodo_id', $nodo_id);
            }
            
            $socio_id = $request->input('socio_id');
            if (!empty($socio_id)) {
                $consulta->where('socio_id', $socio_id);
            }
            
            $desde_fecha = $request->input('desde_fecha');
            $hasta_fecha = $request->input('hasta_fecha');
            
            if ($desde_fecha && $hasta_fecha) {
                $desde_fecha_formateada = \Carbon\Carbon::parse($desde_fecha)->format('Y-m-d');
                $hasta_fecha_formateada = \Carbon\Carbon::parse($hasta_fecha)->format('Y-m-d');
                
                $consulta->whereBetween('fecha', [
                    $desde_fecha_formateada . ' 00:00:00', 
                    $hasta_fecha_formateada . ' 23:59:59'
                ]);
            }
            
            // Si el usuario tiene rol nodo, filtrar solo sus consultas
            if ($roles->contains('nodo')) {
                $consulta->where('nodo_id', $user->nodo_id);
            }
            
            // Obtener resultados ordenados
            $resultados = $consulta->with(['nodo', 'socio'])
                ->orderByRaw('DATE(fecha) ASC, TIME(fecha) ASC')
                ->get()
                ->sortBy([
                    ['nodo.nombre', 'asc'],
                    ['socio.razon_social', 'asc'],
                    ['fecha', 'asc']
                ])
                ->values();
            
            // Obtener información de los filtros aplicados para el PDF
            $filtros = [
                'nodo' => $nodo_id ? \App\Models\Nodo::find($nodo_id)?->nombre : 'TODOS',
                'socio' => $socio_id ? \App\Models\Socio::find($socio_id)?->razon_social : 'TODOS',
                'desde_fecha' => $desde_fecha ? \Carbon\Carbon::parse($desde_fecha)->format('d/m/Y') : '',
                'hasta_fecha' => $hasta_fecha ? \Carbon\Carbon::parse($hasta_fecha)->format('d/m/Y') : '',
            ];
            
            // Generar PDF
            $pdf = Pdf::loadView('admin.administracion.consultar-pdf', compact('resultados', 'filtros'))
                ->setPaper('a4', 'landscape');
            
            $filename = 'Consumos-' . date('Y-m-d-H-i-s') . '.pdf';
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            Log::error('Error generando PDF: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Error al generar PDF',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
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
    public function show(Consulta $consulta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Consulta $consulta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Consulta $consulta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consulta $consulta)
    {
        //
    }
}
