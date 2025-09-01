<?php
namespace App\Http\Controllers;

use App\Models\Socio;
use Illuminate\Http\Request;

class SocioController extends Controller
{
    public function index()
    {
        $socios = Socio::with('nodo')->get();
        return view('admin.socios.index', compact('socios'));
    }

    public function create()
    {
        // Buscar el primer número de socio disponible desde 1
        $usados = Socio::pluck('numero')->map(function($n){ return (int)$n; })->toArray();
        $numero = 1;
        while (in_array($numero, $usados)) {
            $numero++;
        }
        // Obtener todos los nodos para el select
        $nodos = \App\Models\Nodo::all();
        return view('admin.socios.create', compact('numero', 'nodos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required|string|max:10',
            'clase' => 'required|string|max:30',
            'razon_social' => 'required|string|max:100',
            'domicilio' => 'string|max:100',
            'telefono' => 'string|max:50',
            'email' => 'string|max:80',
            'cuit' => 'string|max:11',
            'tipo' => 'string|max:20',
            'estado' => 'string|max:20',
            'nodo_id' => 'required|exists:nodos,id',
            'cod_postal' => 'required',
        ]);

        $socio = new Socio();
        $socio->numero = $request->numero;
        $socio->nodo_id = $request->nodo_id;
        $socio->clase = $request->clase;
        $socio->razon_social = strtoupper($request->razon_social);
        $socio->domicilio = $request->domicilio;
        $socio->cod_postal_id = $request->cod_postal;
        $socio->telefono = $request->telefono;
        $socio->email = $request->email;
        $socio->cuit = $request->cuit;
        $socio->tipo = $request->tipo;
        $socio->estado = empty($request->estado) ? 'Activo' : $request->estado;
        $socio->observacion = $request->observacion;
        $socio->save();

        return redirect()->route('admin.socios.index')
            ->with('mensaje', 'Socio creado con éxito.')
            ->with('icono', 'success');
    }

    public function show($id)
    {
        $socio = Socio::with(['localidad'])->findOrFail($id);
        return view('admin.socios.show', compact('socio'));
    }

    public function edit($id)
    {
        $socio = Socio::with(['localidad'])->findOrFail($id);
        $nodos = \App\Models\Nodo::all();
        return view('admin.socios.edit', compact('socio', 'nodos'));
    }

    public function update(Request $request, $id)
    {

        $socio = Socio::findOrFail($id);
        
        $socio->numero = $request->numero;
        $socio->nodo_id = $request->nodo_id;
        $socio->clase = $request->clase;
        $socio->razon_social = strtoupper($request->razon_social);
        $socio->domicilio = $request->domicilio;
        $socio->cod_postal_id = $request->cod_postal;
        $socio->telefono = $request->telefono;
        $socio->email = $request->email;
        $socio->cuit = $request->cuit;
        $socio->tipo = $request->tipo;
        $socio->estado = empty($request->estado) ? 'Activo' : $request->estado;
        $socio->observacion = $request->observacion;
        $socio->save();

        return redirect()->route('admin.socios.index')
            ->with('mensaje', 'Socio actualizado con éxito.')
            ->with('icono', 'success');
    }

    public function confirmDelete($id)
    {
        $socio = Socio::findOrFail($id);
        return view('admin.socios.delete', compact('socio'));
    }

    public function destroy(Socio $socio)
    {
        $socio->delete();
        return redirect()->route('admin.socios.index')
            ->with('mensaje', 'Socio eliminado con éxito.')
            ->with('icono', 'success');
    }

    public function buscarPorNumero($numero)
    {
        $socio = Socio::where('numero', $numero)->first();
        if ($socio) {
            return response()->json(['success' => true, 'socio' => $socio]);
        } else {
            return response()->json(['success' => false]);
        }
    }
}
