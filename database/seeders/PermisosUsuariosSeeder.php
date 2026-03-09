<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class PermisosUsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear los permisos si no existen
        $permisos = [
            'admin.usuarios.destroy',
            'admin.usuarios.confirm-delete',
            'admin.nodos.confirm-delete',
            'admin.nodos.destroy',
            'admin.socios.confirm-delete',
            'admin.socios.destroy',
            'admin.clientes.confirm-delete',
            'admin.clientes.destroy',
            'admin.administracion.basedatos',
            'admin.administracion.consultar',
            'admin.operaciones.consultar',
        ];
        
        // Crear permisos
        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }
        
        // Asignar todos los permisos a usuarios con rol admin
        $usuariosAdmin = User::role('admin')->get();
        foreach ($usuariosAdmin as $admin) {
            foreach ($permisos as $permiso) {
                $permisoObj = Permission::where('name', $permiso)->first();
                if ($permisoObj) {
                    $admin->givePermissionTo($permisoObj);
                }
            }
        }
        
        // Asignar permiso de basedatos también a usuarios con rol secretaria
        $permisoBaseDatos = Permission::firstOrCreate(['name' => 'admin.administracion.basedatos']);
        $usuariosSecretaria = User::role('secretaria')->get();
        foreach ($usuariosSecretaria as $secretaria) {
            $secretaria->givePermissionTo($permisoBaseDatos);
        }
        
        // Asignar permisos de consulta a roles específicos  
        $permisoConsultaAdmin = Permission::firstOrCreate(['name' => 'admin.administracion.consultar']);
        $permisoConsultaOper = Permission::firstOrCreate(['name' => 'admin.operaciones.consultar']);
        
        // Asignar permisos de consulta a usuarios con rol secretaria (si no se asignó arriba)
        foreach ($usuariosSecretaria as $secretaria) {
            $secretaria->givePermissionTo($permisoConsultaAdmin);
            $secretaria->givePermissionTo($permisoConsultaOper);
        }
        
        // Asignar permisos a usuarios con rol nodo
        $usuariosNodo = User::role('nodo')->get();
        foreach ($usuariosNodo as $nodo) {
            $nodo->givePermissionTo($permisoConsultaAdmin);
            $nodo->givePermissionTo($permisoConsultaOper);
        }
        
        // Asignar permisos a usuarios con rol socio
        $usuariosSocio = User::role('socio')->get();
        foreach ($usuariosSocio as $socio) {
            $socio->givePermissionTo($permisoConsultaOper);
        }
    }
}
