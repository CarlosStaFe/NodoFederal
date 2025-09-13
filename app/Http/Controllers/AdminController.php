<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Nodo;
use App\Models\Socio;
use App\Models\Cliente;
use App\Models\Operacion;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        //Contador de usuarios
        $user = Auth::user();
        if ($user->rol === 'admin' || $user->rol === 'secretaria') {
            $total_usuarios = User::count();
        } elseif ($user->rol === 'nodo') {
            $total_usuarios = User::where('nodo_id', $user->nodo_id)->count();
        } else {
            $total_usuarios = 0;
        }

        //Contador de nodos
        $total_nodos = Nodo::count();

        //Contador de socios
        if ($user->rol === 'admin' || $user->rol === 'secretaria') {
            $total_socios = Socio::count();
        } elseif ($user->rol === 'nodo') {
            $total_socios = Socio::where('nodo_id', $user->nodo_id)->count();
        } else {
            $total_socios = 0;
        }

        //Contador de clientes
        $total_clientes = Cliente::count();

        //Contador de operaciones
        $user = Auth::user();
        if ($user->rol === 'admin' || $user->rol === 'secretaria') {
            $total_operaciones = Operacion::count();
        } elseif ($user->rol === 'nodo') {
            $total_operaciones = Operacion::where('nodo_id', $user->nodo_id)->count();
        } elseif ($user->rol === 'socio') {
            $total_operaciones = Operacion::where('socio_id', $user->id_socio)->count();
        } else {
            $total_operaciones = 0;
        }

        //Contador de consultas
        $user = Auth::user();
        if ($user->rol === 'admin' || $user->rol === 'secretaria') {
            $total_consultas = Operacion::count();
        } elseif ($user->rol === 'nodo') {
            $total_consultas = Operacion::where('nodo_id', $user->nodo_id)->count();
        } elseif ($user->rol === 'socio') {
            $total_consultas = Operacion::where('socio_id', $user->id_socio)->count();
        } else {
            $total_consultas = 0;
        }

        return view('admin.index', compact(
            'total_usuarios',
            'total_nodos',
            'total_socios',
            'total_clientes',
            'total_operaciones',
            'total_consultas'
        ));
    }
}
