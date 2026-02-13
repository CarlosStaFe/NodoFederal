<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

    // Seeder para roles y permisos admin, secretaria, nodos, socios
    $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    $secretaria = Role::firstOrCreate(['name' => 'secretaria', 'guard_name' => 'web']);
    $nodo = Role::firstOrCreate(['name' => 'nodo', 'guard_name' => 'web']);
    $socio = Role::firstOrCreate(['name' => 'socio', 'guard_name' => 'web']);
    // Permiso para mostrar el menú de Operaciones
    Permission::firstOrCreate(['name' => 'admin.operaciones.index'])->syncRoles([$admin, $secretaria, $nodo, $socio]);
    Permission::firstOrCreate(['name' => 'admin.administracion.index'])->syncRoles([$admin, $secretaria, $nodo]);

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('sangreysudor'),
            ]
        );
        $adminUser->assignRole($admin);

        $secretariaUser = User::firstOrCreate(
            ['email' => 'secretaria@secretaria.com'],
            [
                'name' => 'Secretaria',
                'password' => Hash::make('sangreysudor'),
            ]
        );
        $secretariaUser->assignRole($secretaria);

        $nodoUser = User::firstOrCreate(
            ['email' => 'nodo@nodo.com'],
            [
                'name' => 'Nodo',
                'password' => Hash::make('sangreysudor'),
            ]
        );
        $nodoUser->assignRole($nodo);

        $socioUser = User::firstOrCreate(
            ['email' => 'socio@socio.com'],
            [
                'name' => 'Socio',
                'password' => Hash::make('sangreysudor'),
            ]
        );
        $socioUser->assignRole($socio);

        //RUTA PARA EL ADMIN
        Permission::firstOrCreate(['name' => 'admin.index']);

        //RUTA PARA EL ADMIN - ADMINISTRACION
        Permission::firstOrCreate(['name' => 'admin.administracion.consultar'])->syncRoles([$admin, $secretaria, $nodo]);

        //RUTA PARA EL ADMIN - USUARIOS
        Permission::firstOrCreate(['name' => 'admin.usuarios.index'])->syncRoles([$admin, $secretaria, $nodo]);
        Permission::firstOrCreate(['name' => 'admin.usuarios.create'])->syncRoles([$admin, $secretaria, $nodo]);
        Permission::firstOrCreate(['name' => 'admin.usuarios.store'])->syncRoles([$admin, $secretaria, $nodo]);
        Permission::firstOrCreate(['name' => 'admin.usuarios.show'])->syncRoles([$admin, $secretaria, $nodo]);
        Permission::firstOrCreate(['name' => 'admin.usuarios.edit'])->syncRoles([$admin, $secretaria, $nodo]);
        Permission::firstOrCreate(['name' => 'admin.usuarios.update'])->syncRoles([$admin, $secretaria, $nodo]);
        Permission::firstOrCreate(['name' => 'admin.usuarios.confirm-delete'])->syncRoles([$admin, $secretaria, $nodo]);
        Permission::firstOrCreate(['name' => 'admin.usuarios.destroy'])->syncRoles([$admin, $secretaria, $nodo]);

        //RUTA PARA EL ADMIN - NODOS
        Permission::firstOrCreate(['name' => 'admin.nodos.index'])->syncRoles([$admin, $secretaria]);
        Permission::firstOrCreate(['name' => 'admin.nodos.create'])->syncRoles([$admin, $secretaria]);
        Permission::firstOrCreate(['name' => 'admin.nodos.store'])->syncRoles([$admin, $secretaria]);
        Permission::firstOrCreate(['name' => 'admin.nodos.show'])->syncRoles([$admin, $secretaria]);
        Permission::firstOrCreate(['name' => 'admin.nodos.edit'])->syncRoles([$admin, $secretaria]);
        Permission::firstOrCreate(['name' => 'admin.nodos.update'])->syncRoles([$admin, $secretaria]);
        Permission::firstOrCreate(['name' => 'admin.nodos.confirm-delete'])->syncRoles([$admin, $secretaria]);
        Permission::firstOrCreate(['name' => 'admin.nodos.destroy'])->syncRoles([$admin, $secretaria]);
        Permission::firstOrCreate(['name' => 'admin.nodos.consultar'])->syncRoles([$admin, $secretaria, $nodo]);

        //RUTA PARA EL ADMIN - SOCIOS
        Permission::firstOrCreate(['name' => 'admin.socios.index'])->syncRoles([$admin,$secretaria,$nodo]);
        Permission::firstOrCreate(['name' => 'admin.socios.create'])->syncRoles([$admin,$secretaria,$nodo]);
        Permission::firstOrCreate(['name' => 'admin.socios.store'])->syncRoles([$admin,$secretaria,$nodo]);
        Permission::firstOrCreate(['name' => 'admin.socios.show'])->syncRoles([$admin,$secretaria,$nodo]);
        Permission::firstOrCreate(['name' => 'admin.socios.edit'])->syncRoles([$admin,$secretaria,$nodo]);
        Permission::firstOrCreate(['name' => 'admin.socios.update'])->syncRoles([$admin,$secretaria,$nodo]);
        Permission::firstOrCreate(['name' => 'admin.socios.confirm-delete'])->syncRoles([$admin,$secretaria,$nodo]);
        Permission::firstOrCreate(['name' => 'admin.socios.destroy'])->syncRoles([$admin,$secretaria,$nodo]);
        Permission::firstOrCreate(['name' => 'admin.socios.buscar-por-numero'])->syncRoles([$admin,$secretaria,$nodo]);

        //RUTA PARA EL ADMIN - CLIENTES
        Permission::firstOrCreate(['name' => 'admin.clientes.index'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::firstOrCreate(['name' => 'admin.clientes.create'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::firstOrCreate(['name' => 'admin.clientes.store'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::firstOrCreate(['name' => 'admin.clientes.show'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::firstOrCreate(['name' => 'admin.clientes.edit'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::firstOrCreate(['name' => 'admin.clientes.update'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::firstOrCreate(['name' => 'admin.clientes.confirm-delete'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::firstOrCreate(['name' => 'admin.clientes.destroy'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::firstOrCreate(['name' => 'admin.clientes.buscar-por-cuit'])->syncRoles([$admin,$secretaria,$nodo,$socio]);

        //RUTA PARA EL ADMIN - OPERACIONES
        Permission::firstOrCreate(['name' => 'admin.operaciones.consultar'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::firstOrCreate(['name' => 'admin.operaciones.informe'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::firstOrCreate(['name' => 'admin.operaciones.pdf'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::firstOrCreate(['name' => 'admin.operaciones.cargar'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::firstOrCreate(['name' => 'admin.operaciones.store'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::firstOrCreate(['name' => 'admin.operaciones.show'])->syncRoles([$admin,$secretaria,$nodo,$socio]);

        Permission::firstOrCreate(['name' => 'admin.operaciones.afectar'])->syncRoles([$admin,$secretaria,$nodo,$socio]);

        //RUTA PARA EL ADMIN - ADMINISTRACION BASE DE DATOS
        Permission::firstOrCreate(['name' => 'admin.administracion.basedatos'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'admin.administracion.consultar'])->syncRoles([$admin, $secretaria]);

        $this->call([
            LocalidadesSeeder::class,
            NodosSeeder::class,
            SociosSeeder::class,
        ]);

    }
}
