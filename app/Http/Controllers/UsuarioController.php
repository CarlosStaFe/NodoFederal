<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UsuarioController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $roles = $user->roles->pluck('name');
        if ($roles->contains('admin') || $roles->contains('secretaria')) {
            $usuarios = User::with(['nodo', 'socio'])->get();
        } elseif ($roles->contains('nodo')) {
            $usuarios = User::with(['nodo', 'socio'])->where('nodo_id', $user->nodo_id)->get();
        } else {
            $usuarios = collect();
        }
        if ($usuarios->isEmpty()) {
            return view('admin.usuarios.index', compact('usuarios'))->with('mensaje', 'No hay usuarios para mostrar según su rol.');
        }
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $user = Auth::user();
        $roles = $user->roles->pluck('name');
        if ($roles->contains('nodo')) {
            $nodos = \App\Models\Nodo::where('id', $user->nodo_id)->get();
            $socios = \App\Models\Socio::where('nodo_id', $user->nodo_id)->orderBy('razon_social', 'asc')->get();
        } else {
            $nodos = \App\Models\Nodo::orderBy('nombre', 'asc')->get();
            $socios = \App\Models\Socio::orderBy('razon_social', 'asc')->get();
        }
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
            'nodo_id' => 'required_unless:rol,admin,secretaria|nullable|exists:nodos,id',
            'socio_id' => 'required_unless:rol,admin,secretaria,nodo|nullable|exists:socios,id',
        ]);

        $currentUser = Auth::user();
        $currentUserRoles = $currentUser->roles->pluck('name');
        
        // Validación adicional para usuarios con rol 'nodo'
        if ($currentUserRoles->contains('nodo')) {
            // Si el usuario actual es nodo, debe usar su propio nodo_id
            if ($request->nodo_id != $currentUser->nodo_id) {
                return redirect()->back()
                    ->withErrors(['nodo_id' => 'No tiene permisos para crear usuarios en otros nodos.'])
                    ->withInput();
            }
            
            // Solo puede crear usuarios con rol 'socio'
            if ($request->rol !== 'socio') {
                return redirect()->back()
                    ->withErrors(['rol' => 'Solo puede crear usuarios con rol Socio.'])
                    ->withInput();
            }
            
            // Si se selecciona un socio, verificar que pertenece al nodo del usuario
            if (!empty($request->socio_id)) {
                $socio = \App\Models\Socio::find($request->socio_id);
                if (!$socio || $socio->nodo_id != $currentUser->nodo_id) {
                    return redirect()->back()
                        ->withErrors(['socio_id' => 'El socio seleccionado no pertenece a su nodo.'])
                        ->withInput();
                }
            }
        }

        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->password);
        if (in_array($request->rol, ['admin', 'secretaria'])) {
            $usuario->nodo_id = 24;
            $usuario->socio_id = 1;
        } else {
            $usuario->nodo_id = $request->nodo_id;
            // Para rol 'nodo', socio_id puede ser null
            $usuario->socio_id = $request->rol === 'nodo' ? null : $request->socio_id;
        }
        $usuario->save();

        // Asignar el rol seleccionado
        $usuario->assignRole($request->rol);

        // Enviar email con usuario y contraseña
        Mail::send([], [], function ($message) use ($usuario, $request) {
            $appUrl = preg_replace('/^https?:\/\//', '', env('APP_URL'));
            
            // Obtener datos del nodo y socio
            $nodo = $usuario->nodo ? $usuario->nodo->nombre : 'No asignado';
            $socio = $usuario->socio ? $usuario->socio->razon_social : 'No asignado';
            
            $message->to($usuario->email)
                ->subject('Usuario creado en Nodo Federal-NO CONTESTAR ESTE MENSAJE')
                ->html('<p>Su usuario ha sido creado.</p><br><p><b>Usuario:</b> ' . $usuario->email . '</p><p><b>Contraseña:</b> ' . $request->password . '</p><p><b>Nodo:</b> ' . $nodo . '</p><p><b>Socio:</b> ' . $socio . '</p><br><p>Link de la aplicación: <a href="//' . $appUrl . '">' . $appUrl . '</a></p>');
        });

        return redirect()->route('admin.usuarios.index')
            ->with('mensaje', 'Usuario creado con éxito.')
            ->with('icono', 'success');
    }

    public function show($id)
    {
        $usuario = User::with(['nodo', 'socio', 'createdBy', 'updatedBy'])->findOrFail($id);
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
        $currentUser = Auth::user();
        $currentUserRoles = $currentUser->roles->pluck('name');
        
        // Determinar si el socio es requerido según el rol y permisos
        $socioRequerido = 'required_if:rol,socio';
        
        // Si el usuario actual no es admin/secretaria, mantener reglas más estrictas
        if (!$currentUserRoles->contains('admin') && !$currentUserRoles->contains('secretaria')) {
            $socioRequerido = 'required_unless:rol,admin,secretaria,nodo';
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'rol' => 'required|in:admin,secretaria,nodo,socio',
            'nodo_id' => 'required_if:rol,nodo,socio|nullable|exists:nodos,id',
            'socio_id' => $socioRequerido . '|nullable|exists:socios,id',
        ]);

        $usuario = User::findOrFail($id);
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        
        if (in_array($request->rol, ['admin', 'secretaria'])) {
            $usuario->nodo_id = null;
            $usuario->socio_id = null;
        } elseif ($request->rol == 'nodo') {
            $usuario->nodo_id = $request->nodo_id;
            // Para usuarios nodo, el socio es opcional si lo edita admin/secretaria
            $usuario->socio_id = $request->socio_id ?: null;
        } else { // rol socio
            $usuario->nodo_id = $request->nodo_id;
            $usuario->socio_id = $request->socio_id;
        }
        
        if ($request->password) {
            $usuario->password = bcrypt($request->password);
        }
        $usuario->save();

        // Quitar roles anteriores y asignar el nuevo
        $usuario->syncRoles([$request->rol]);

        // Enviar email con usuario y contraseña si se actualizó la contraseña
        if ($request->password) {
            Mail::send([], [], function ($message) use ($usuario, $request) {
                $appUrl = preg_replace('/^https?:\/\//', '', env('APP_URL'));
                
                // Obtener datos del nodo y socio
                $nodo = $usuario->nodo ? $usuario->nodo->nombre : 'No asignado';
                $socio = $usuario->socio ? $usuario->socio->razon_social : 'No asignado';
                
                $message->to($usuario->email)
                    ->subject('Usuario creado en Nodo Federal-NO CONTESTAR ESTE MENSAJE')
                    ->html('<p>Su usuario ha sido creado.</p><br><p><b>Usuario:</b> ' . $usuario->email . '</p><p><b>Contraseña:</b> ' . $request->password . '</   p><p><b>Nodo:</b> ' . $nodo . '</p><p><b>Socio:</b> ' . $socio . '</p><br><p>Link de la aplicación: <a href="//' . $appUrl . '">' . $appUrl .  '</a></p>');
            });
        }

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
