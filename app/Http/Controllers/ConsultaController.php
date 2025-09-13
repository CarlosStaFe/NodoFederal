<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use Illuminate\Http\Request;

class ConsultaController extends Controller
{
    public function consultar()
    {
        $user = auth()->user();
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

        // Filtrar consultas si hay parámetros
        $consulta = collect();
        $nodo_id = request('nodo_id');
        $socio_id = request('socio_id');
        $desde_fecha = request('desde_fecha');
        $hasta_fecha = request('hasta_fecha');
        if ($nodo_id && $socio_id && $desde_fecha && $hasta_fecha) {
            $consulta = \App\Models\Consulta::where('nodo_id', $nodo_id)
                ->where('socio_id', $socio_id)
                ->whereBetween('fecha', [$desde_fecha, $hasta_fecha])
                ->get();
        }
        return view('admin.administracion.consultar', compact('nodos', 'socios', 'consulta'));
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
