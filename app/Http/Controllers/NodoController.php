<?php

namespace App\Http\Controllers;

use App\Models\Nodo;
use Illuminate\Http\Request;

class NodoController extends Controller
{
    public function index()
    {
        $nodos = Nodo::all();
        return view('admin.nodos.index', compact('nodos'));
    }

    public function create()
    {
        // Buscar el primer número de nodo disponible desde 1
        $usados = Nodo::pluck('numero')->map(function($n){ return (int)$n; })->toArray();
        $numero = 1;
        while (in_array($numero, $usados)) {
            $numero++;
        }
        // Por defecto, factura igual al número sugerido
        $factura = $numero;
        return view('admin.nodos.create', compact('numero', 'factura'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'factura' => 'required|string|max:10',
            'nombre' => 'required|string|max:255',
            'domicilio' => 'string|max:100',
            'telefono' => 'string|max:50',
            'email' => 'string|max:80',
            'cuit' => 'string|max:11',
            'tipo' => 'string|max:20',
            'estado' => 'string|max:20',
        ]);

        $nodo = new Nodo();
        $nodo->numero = $request->numero;
        $nodo->factura = $request->factura;
        $nodo->nombre = strtoupper($request->nombre);
        $nodo->telefono = $request->telefono;
        $nodo->domicilio = $request->domicilio;
        $nodo->cod_postal_id = $request->cod_postal;
        $nodo->telefono = $request->telefono;
        $nodo->email = $request->email;
        $nodo->cuit = $request->cuit;
        $nodo->tipo = $request->tipo;
        $nodo->estado = empty($request->estado) ? 'Activo' : $request->estado;
        $nodo->observacion = $request->observacion;
        $nodo->save();

        return redirect()->route('admin.nodos.index')
            ->with('mensaje', 'Nodo creado con éxito.')
            ->with('icono', 'success');
    }

    public function show($id)
    {
        $nodo = Nodo::with('localidad')->findOrFail($id);
        return view('admin.nodos.show', compact('nodo'));
    }

    public function edit($id)
    {
        $nodo = Nodo::with('localidad')->findOrFail($id);
        return view('admin.nodos.edit', compact('nodo'));
    }

    public function update(Request $request, $id)
    {
        $nodo = Nodo::findOrFail($id);

        $request->validate([
            'factura' => 'required|string|max:10',
            'nombre' => 'required|string|max:255',
            'domicilio' => 'string|max:100',
            'telefono' => 'string|max:50',
            'email' => 'string|max:80',
            'cuit' => 'string|max:11',
            'tipo' => 'string|max:20',
            'estado' => 'string|max:20',
        ]);

        $nodo->numero = $request->numero;
        $nodo->factura = $request->factura;
        $nodo->nombre = strtoupper($request->nombre);
        $nodo->telefono = $request->telefono;
        $nodo->domicilio = $request->domicilio;
        $nodo->cod_postal_id = $request->cod_postal;
        $nodo->telefono = $request->telefono;
        $nodo->email = $request->email;
        $nodo->cuit = $request->cuit;
        $nodo->tipo = $request->tipo;
        $nodo->estado = empty($request->estado) ? 'Activo' : $request->estado;
        $nodo->observacion = $request->observacion;
        $nodo->save();

        return redirect()->route('admin.nodos.index')
            ->with('mensaje', 'Nodo actualizado con éxito.')
            ->with('icono', 'success');
    }

    public function confirmDelete($id)
    {
        $nodo = Nodo::findOrFail($id);
        return view('admin.nodos.delete', compact('nodo'));
    }

    public function destroy($id)
    {
        $nodo = Nodo::findOrFail($id);

        $nodo->delete();
        
        return redirect()->route('admin.nodos.index')
            ->with('mensaje', 'Nodo eliminado con éxito.')
            ->with('icono', 'success');
    }
}
