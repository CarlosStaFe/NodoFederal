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
                $resultados = $consulta->with(['nodo', 'socio', 'user'])
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
            $resultados = $consulta->with(['nodo', 'socio', 'user'])
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
     * Generar archivo Excel con los resultados de la consulta
     */
    public function generarExcel(Request $request)
    {
        try {
            $nodo_id = $request->input('nodo_id');
            $socio_id = $request->input('socio_id');
            $desde_fecha = $request->input('desde_fecha');
            $hasta_fecha = $request->input('hasta_fecha');

            // Query base con joins
            $query = Consulta::query()
                ->leftJoin('nodos', 'consultas.nodo_id', '=', 'nodos.id')
                ->leftJoin('socios', 'consultas.socio_id', '=', 'socios.id')
                ->leftJoin('users', 'consultas.user_id', '=', 'users.id')
                ->select(
                    'consultas.*',
                    'nodos.nombre as nodo_nombre',
                    'socios.razon_social as socio_razon_social',
                    'users.name as usuario_nombre'
                );

            // Aplicar filtros
            if ($nodo_id) {
                $query->where('consultas.nodo_id', $nodo_id);
            }

            if ($socio_id) {
                $query->where('consultas.socio_id', $socio_id);
            }

            if ($desde_fecha) {
                $query->where('consultas.fecha', '>=', $desde_fecha);
            }

            if ($hasta_fecha) {
                $query->where('consultas.fecha', '<=', $hasta_fecha . ' 23:59:59');
            }

            $resultados = $query->orderBy('nodos.nombre')
                ->orderBy('socios.razon_social')
                ->orderBy('consultas.fecha')
                ->get();

            // Crear el archivo Excel
            $filename = 'Consulta_Consumos_' . date('Y-m-d_H-i-s') . '.xls';
            
            // Headers para XLS
            $headers = [
                'Content-Type' => 'application/vnd.ms-excel',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'max-age=0',
                'Pragma' => 'public',
            ];

            // Crear contenido HTML que Excel interpretará como XLS
            $htmlContent = '<!DOCTYPE html>';
            $htmlContent .= '<html>';
            $htmlContent .= '<head>';
            $htmlContent .= '<meta charset="UTF-8">';
            $htmlContent .= '<style>';
            $htmlContent .= 'table { border-collapse: collapse; width: 100%; }';
            $htmlContent .= 'th, td { border: 1px solid black; padding: 8px; text-align: left; }';
            $htmlContent .= 'th { background-color: #4CAF50; color: white; font-weight: bold; }';
            $htmlContent .= '</style>';
            $htmlContent .= '</head>';
            $htmlContent .= '<body>';
            $htmlContent .= '<table>';
            
            // Headers de la tabla
            $htmlContent .= '<tr>';
            $htmlContent .= '<th>NRO.</th>';
            $htmlContent .= '<th>FECHA</th>';
            $htmlContent .= '<th>HORA</th>';
            $htmlContent .= '<th>TIPO</th>';
            $htmlContent .= '<th>CUIT</th>';
            $htmlContent .= '<th>APELLIDO Y NOMBRES</th>';
            $htmlContent .= '<th>NODO</th>';
            $htmlContent .= '<th>SOCIO</th>';
            $htmlContent .= '<th>USUARIO</th>';
            $htmlContent .= '</tr>';;

            // Datos de la tabla
            foreach ($resultados as $consulta) {
                $fecha = $consulta->fecha ? \Carbon\Carbon::parse($consulta->fecha) : null;
                $htmlContent .= '<tr>';
                $htmlContent .= '<td>' . htmlspecialchars($consulta->numero ?? '') . '</td>';
                $htmlContent .= '<td>' . ($fecha ? $fecha->format('d/m/Y') : '') . '</td>';
                $htmlContent .= '<td>' . ($fecha ? $fecha->format('H:i') : '') . '</td>';
                $htmlContent .= '<td>' . htmlspecialchars($consulta->tipo ?? '') . '</td>';
                $htmlContent .= '<td>' . htmlspecialchars($consulta->cuit ?? '') . '</td>';
                $htmlContent .= '<td>' . htmlspecialchars($consulta->apelynombres ?? '') . '</td>';
                $htmlContent .= '<td>' . htmlspecialchars($consulta->nodo_nombre ?? '') . '</td>';
                $htmlContent .= '<td>' . htmlspecialchars($consulta->socio_razon_social ?? '') . '</td>';
                $htmlContent .= '<td>' . htmlspecialchars($consulta->usuario_nombre ?? '') . '</td>';
                $htmlContent .= '</tr>';
            }
            
            $htmlContent .= '</table>';
            $htmlContent .= '</body>';
            $htmlContent .= '</html>';

            // Retornar como descarga
            return response($htmlContent, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Error generando Excel: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Error al generar Excel',
                'message' => $e->getMessage()
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
