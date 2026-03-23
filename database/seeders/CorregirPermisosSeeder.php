<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CorregirPermisosSeeder extends Seeder
{
    /**
     * Corregir permisos específicos
     */
    public function run(): void
    {
        // Obtener roles
        $admin = Role::where('name', 'admin')->first();
        $secretaria = Role::where('name', 'secretaria')->first();
        $nodo = Role::where('name', 'nodo')->first();
        $socio = Role::where('name', 'socio')->first();

        // Corregir el permiso de consultar para incluir el rol nodo
        $permisoConsultar = Permission::where('name', 'admin.administracion.consultar')->first();
        
        if ($permisoConsultar) {
            // Sincronizar con los roles correctos (admin, secretaria, nodo)
            $permisoConsultar->syncRoles([$admin, $secretaria, $nodo]);
            echo "✓ Permiso 'admin.administracion.consultar' actualizado para roles: admin, secretaria, nodo\n";
        } else {
            // Crear el permiso si no existe
            $permisoConsultar = Permission::create(['name' => 'admin.administracion.consultar']);
            $permisoConsultar->syncRoles([$admin, $secretaria, $nodo]);
            echo "✓ Permiso 'admin.administracion.consultar' creado para roles: admin, secretaria, nodo\n";
        }
    }
}