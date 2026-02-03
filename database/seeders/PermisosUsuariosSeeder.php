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
            'admin.socios.confirm-delete',
            'admin.clientes.confirm-delete',
            'admin.administracion.basedatos',
        ];
        
        // Asignar permisos al usuario admin (ID 1)
        $user = User::find(1);
        foreach ($permisos as $permiso) {
            $permisoObj = Permission::firstOrCreate(['name' => $permiso]);
            if ($user) {
                $user->givePermissionTo($permisoObj);
            }
        }
        
        // Asignar permiso de basedatos también a usuarios con rol secretaria
        $permisoBaseDatos = Permission::firstOrCreate(['name' => 'admin.administracion.basedatos']);
        $usuariosSecretaria = User::role('secretaria')->get();
        foreach ($usuariosSecretaria as $secretaria) {
            $secretaria->givePermissionTo($permisoBaseDatos);
        }
    }
}
