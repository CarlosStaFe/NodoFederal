<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Nodo;
use App\Models\Socio;
use App\Models\Cliente;

class AdminController extends Controller
{
    public function index()
    {
        $total_usuarios = User::count();
        $total_nodos = Nodo::count();
        $total_socios = Socio::count();
        $total_clientes = Cliente::count();

        return view('admin.index', compact(
            'total_usuarios',
            'total_nodos',
            'total_socios',
            'total_clientes'
        ));
    }
}
