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
        $total_usuarios = User::count();
        $total_nodos = Nodo::count();
        $total_socios = Socio::count();
        $total_clientes = Cliente::count();
        $user = Auth::user();
        if ($user->rol === 'admin' || $user->rol === 'secretaria') {
            $total_operaciones = Operacion::count();
        } elseif ($user->rol === 'nodo') {
            $total_operaciones = Operacion::where('nodo_id', $user->nodo_id)->count();
        } elseif ($user->rol === 'socio') {
            // Suponiendo que el socio tiene relación con un nodo
            $total_operaciones = Operacion::where('nodo_id', $user->nodo_id)->count();
        } else {
            $total_operaciones = 0;
        }
        if ($user->rol === 'admin' || $user->rol === 'secretaria') {
            $total_consultas = Operacion::count();
        } elseif ($user->rol === 'nodo') {
            $total_consultas = Operacion::where('nodo_id', $user->nodo_id)->count();
        } elseif ($user->rol === 'socio') {
            // Suponiendo que el socio tiene relación con un nodo
            $total_consultas = Operacion::where('nodo_id', $user->nodo_id)->count();
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
