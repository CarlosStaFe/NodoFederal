<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::with(['nodo', 'socio'])->get();
        //return response()->json($usuarios);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $nodos = \App\Models\Nodo::all();
        $socios = \App\Models\Socio::all();
        return view('admin.usuarios.create', compact('nodos', 'socios'));
    }

    public function store(Request $request)
    {
        //$datos = request()->all();
        //return response()->json($datos);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'rol' => 'required|in:admin,secretaria,nodo,socio',
        ]);

        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->password);
        if (in_array($request->rol, ['admin', 'secretaria'])) {
            $usuario->nodo_id = null;
            $usuario->socio_id = null;
        } else {
            $usuario->nodo_id = $request->nodo_id;
            $usuario->socio_id = $request->socio_id;
        }
        $usuario->save();

        // Asignar el rol seleccionado
        $usuario->assignRole($request->rol);

        return redirect()->route('admin.usuarios.index')
            ->with('mensaje', 'Usuario creado con éxito.')
            ->with('icono', 'success');
    }

    public function show($id)
    {
    $usuario = User::with(['nodo', 'socio'])->findOrFail($id);
    return view('admin.usuarios.show', compact('usuario'));
    }

    public function edit($id)
    {
    $nodos = \App\Models\Nodo::all();
    $socios = \App\Models\Socio::all();
    $usuario = User::with(['nodo', 'socio'])->findOrFail($id);
    return view('admin.usuarios.edit', compact('usuario', 'nodos', 'socios'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'rol' => 'required|in:admin,secretaria,nodo,socio',
        ]);

        $usuario = User::findOrFail($id);
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        if (in_array($request->rol, ['admin', 'secretaria'])) {
            $usuario->nodo_id = null;
            $usuario->socio_id = null;
        } else {
            $usuario->nodo_id = $request->nodo_id;
            $usuario->socio_id = $request->socio_id;
        }
        if ($request->password) {
            $usuario->password = bcrypt($request->password);
        }
        $usuario->save();

        // Quitar roles anteriores y asignar el nuevo
        $usuario->syncRoles([$request->rol]);

        return redirect()->route('admin.usuarios.index')
            ->with('mensaje', 'Usuario actualizado con éxito.')
            ->with('icono', 'success');
    }

    public function confirmDelete($id)
    {
        $usuario = User::findOrFail($id);
        return view('admin.usuarios.delete', compact('usuario'));
    }

    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->route('admin.usuarios.index')
            ->with('mensaje', 'Usuario eliminado con éxito.')
            ->with('icono', 'success');
    }

}
