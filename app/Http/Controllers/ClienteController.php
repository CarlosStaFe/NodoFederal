<?php
namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use \App\Helpers\CuitHelper;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all();
        return view('admin.clientes.index', compact('clientes'));
    }

    public function create()
    {
        // Si hay datos previos, calcular el CUIT sugerido
        $cuit = null;
        $dni = request('documento');
        $sexo = request('sexo');
        if ($dni && $sexo) {
            $cuit = CuitHelper::calcularCuit($dni, $sexo);
        }
        return view('admin.clientes.create', compact('cuit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipodoc' => 'required|string|max:5',
            'sexo' => 'required|string|max:1',
            'documento' => 'required|string|max:8',
            'cuit' => 'string|max:11',
            'apelnombres' => 'string|max:50',
            'nacimiento' => 'date|max:20',
            'domicilio' => 'string|max:100',
            'cod_postal' => 'required|exists:localidades,id',
            'telefono' => 'string|max:50',
            'email' => 'string|max:80',
            'estado' => 'string|max:20',
            'cod_postal' => 'required',
        ]);

        $cliente = new Cliente();
        $cliente->tipodoc = $request->tipodoc;
        $cliente->documento = $request->documento;
        $cliente->sexo = $request->sexo;
        $cliente->cuit = $request->cuit;
        $cliente->apelnombres = strtoupper($request->apelnombres);
        $cliente->nacimiento = $request->nacimiento;
        $cliente->domicilio = $request->domicilio;
        $cliente->cod_postal_id = $request->cod_postal;
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email;
        $cliente->estado = empty($request->estado) ? 'Activo' : $request->estado;
        $cliente->fechaestado = now();
        $cliente->observacion = $request->observacion;
        $cliente->save();

        return redirect()->route('admin.clientes.index')
            ->with('mensaje', 'Cliente creado con éxito.')
            ->with('icono', 'success');
    }

    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('admin.clientes.show', compact('cliente'));
    }

    public function edit($id)
    {
        $cliente = Cliente::with(['localidad'])->findOrFail($id);
        return view('admin.clientes.edit', compact('cliente'));
    }

   public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $request->validate([
            'tipodoc' => 'required|string|max:5',
            'sexo' => 'required|string|max:1',
            'documento' => 'required|string|max:8',
            'cuit' => 'string|max:11',
            'apelnombres' => 'string|max:50',
            'nacimiento' => 'date|max:20',
            'domicilio' => 'string|max:100',
            'cod_postal' => 'required|exists:localidades,id',
            'telefono' => 'string|max:50',
            'email' => 'string|max:80',
            'estado' => 'string|max:20',
            'cod_postal' => 'required',
        ]);

        $cliente->tipodoc = $request->tipodoc;
        $cliente->documento = $request->documento;
        $cliente->sexo = $request->sexo;
        $cliente->cuit = $request->cuit;
        $cliente->apelnombres = strtoupper($request->apelnombres);
        $cliente->nacimiento = $request->nacimiento ? date('Y-m-d', strtotime($request->nacimiento)) : null;
        $cliente->domicilio = $request->domicilio;
        $cliente->cod_postal_id = $request->cod_postal;
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email;
        $cliente->estado = empty($request->estado) ? 'Activo' : $request->estado;
        $cliente->fechaestado = $request->fechaestado ? date('Y-m-d', strtotime($request->fechaestado)) : null;
        $cliente->observacion = $request->observacion;
        $cliente->save();

        return redirect()->route('admin.clientes.index')
            ->with('mensaje', 'Cliente actualizado con éxito.')
            ->with('icono', 'success');
    }

    public function confirmDelete($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('admin.clientes.delete', compact('cliente'));
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('admin.clientes.index')
            ->with('mensaje', 'Cliente eliminado con éxito.')
            ->with('icono', 'success');
    }

    /**
    * Endpoint para calcular el CUIT vía AJAX
    */
    public function calcularCuit(Request $request)
    {
        $dni = $request->input('dni');
        $sexo = $request->input('sexo');
        $cuit = null;
        if ($dni && $sexo) {
            $cuit = CuitHelper::calcularCuit($dni, $sexo);
        }
        return response()->json(['cuit' => $cuit]);
    }
    
    /**
     * Buscar cliente por CUIT (AJAX)
     */
    public function buscarPorCuit($cuit)
    {
        $cliente = Cliente::where('cuit', $cuit)->first();
        if ($cliente) {
            return response()->json(['success' => true, 'cliente' => $cliente]);
        } else {
            return response()->json(['success' => false]);
        }
    }

}
