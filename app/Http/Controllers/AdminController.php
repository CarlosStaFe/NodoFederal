<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Nodo;
use App\Models\Socio;
use App\Models\Cliente;
use App\Models\Operacion;
use App\Models\Consulta;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $roles = $user->roles->pluck('name');

        //Contador de usuarios
        if ($roles->contains('admin') || $roles->contains('secretaria')) {
            $total_usuarios = User::count();
        } elseif ($roles->contains('nodo')) {
            $total_usuarios = User::where('nodo_id', $user->nodo_id)->count();
        } else {
            $total_usuarios = 0;
        }

        //Contador de nodos
        $total_nodos = Nodo::count();

        //Contador de socios
        if ($roles->contains('admin') || $roles->contains('secretaria')) {
            $total_socios = Socio::count();
        } elseif ($roles->contains('nodo')) {
            $total_socios = Socio::where('nodo_id', $user->nodo_id)->count();
        } else {
            $total_socios = 0;
        }

        //Contador de clientes
        $total_clientes = Cliente::count();

        //Contador de operaciones
        if ($roles->contains('admin') || $roles->contains('secretaria')) {
            $total_operaciones = Operacion::where('tipo', 'Solicitante')->count();
        } elseif ($roles->contains('nodo')) {
            $total_operaciones = Operacion::where('nodo_id', $user->nodo_id)
            ->where('tipo', 'Solicitante')
            ->count();
        } elseif ($roles->contains('socio')) {
            $total_operaciones = Operacion::where('socio_id', $user->socio_id)
            ->where('tipo', 'Solicitante')
            ->count();
        } else {
            $total_operaciones = 0;
        }

        //Contador de consultas
        if ($roles->contains('admin') || $roles->contains('secretaria')) {
            $total_consultas = Consulta::count();
        } elseif ($roles->contains('nodo')) {
            $total_consultas = Consulta::where('nodo_id', $user->nodo_id)->count();
        } elseif ($roles->contains('socio')) {
            $total_consultas = Consulta::where('socio_id', $user->socio_id)->count();
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
