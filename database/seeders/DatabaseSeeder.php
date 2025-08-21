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
        //seeder para roles y permisos admin, secretaria, nodos, socios
        $admin = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $secretaria = Role::create(['name' => 'secretaria', 'guard_name' => 'web']);
        $nodo = Role::create(['name' => 'nodo', 'guard_name' => 'web']);
        $socio = Role::create(['name' => 'socio', 'guard_name' => 'web']);

        User::create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => Hash::make('sangreysudor'),
        ])->assignRole($admin);
        User::create([
            'name' => 'Secretaria',
            'email' => 'secretaria@secretaria.com',
            'password' => Hash::make('sangreysudor'),
        ])->assignRole($secretaria);
        User::create([
            'name' => 'Nodo',
            'email' => 'nodo@nodo.com',
            'password' => Hash::make('sangreysudor'),
        ])->assignRole($nodo);
        User::create([
            'name' => 'Socio',
            'email' => 'socio@socio.com',
            'password' => Hash::make('sangreysudor'),
        ])->assignRole($socio);

        //RUTA PARA EL ADMIN
        Permission::create(['name' => 'admin.index']);

        //RUTA PARA EL ADMIN - USUARIOS
        Permission::create(['name' => 'admin.usuarios.index'])->syncRoles([$admin, $secretaria]);
        Permission::create(['name' => 'admin.usuarios.create'])->syncRoles([$admin, $secretaria]);
        Permission::create(['name' => 'admin.usuarios.store'])->syncRoles([$admin, $secretaria]);
        Permission::create(['name' => 'admin.usuarios.show'])->syncRoles([$admin, $secretaria]);
        Permission::create(['name' => 'admin.usuarios.edit'])->syncRoles([$admin, $secretaria]);
        Permission::create(['name' => 'admin.usuarios.update'])->syncRoles([$admin, $secretaria]);
        Permission::create(['name' => 'admin.usuarios.confirmDelete'])->syncRoles([$admin, $secretaria]);
        Permission::create(['name' => 'admin.usuarios.destroy'])->syncRoles([$admin, $secretaria]);

        //RUTA PARA EL ADMIN - NODOS
        Permission::create(['name' => 'admin.nodos.index'])->syncRoles([$admin, $secretaria]);
        Permission::create(['name' => 'admin.nodos.create'])->syncRoles([$admin, $secretaria]);
        Permission::create(['name' => 'admin.nodos.store'])->syncRoles([$admin, $secretaria]);
        Permission::create(['name' => 'admin.nodos.show'])->syncRoles([$admin, $secretaria]);
        Permission::create(['name' => 'admin.nodos.edit'])->syncRoles([$admin, $secretaria]);
        Permission::create(['name' => 'admin.nodos.update'])->syncRoles([$admin, $secretaria]);
        Permission::create(['name' => 'admin.nodos.confirmDelete'])->syncRoles([$admin, $secretaria]);
        Permission::create(['name' => 'admin.nodos.destroy'])->syncRoles([$admin, $secretaria]);

        //RUTA PARA EL ADMIN - SOCIOS
        Permission::create(['name' => 'admin.socios.index'])->syncRoles([$admin,$secretaria,$nodo]);
        Permission::create(['name' => 'admin.socios.create'])->syncRoles([$admin,$secretaria,$nodo]);
        Permission::create(['name' => 'admin.socios.store'])->syncRoles([$admin,$secretaria,$nodo]);
        Permission::create(['name' => 'admin.socios.show'])->syncRoles([$admin,$secretaria,$nodo]);
        Permission::create(['name' => 'admin.socios.edit'])->syncRoles([$admin,$secretaria,$nodo]);
        Permission::create(['name' => 'admin.socios.update'])->syncRoles([$admin,$secretaria,$nodo]);
        Permission::create(['name' => 'admin.socios.confirmDelete'])->syncRoles([$admin,$secretaria,$nodo]);
        Permission::create(['name' => 'admin.socios.destroy'])->syncRoles([$admin,$secretaria,$nodo]);

        //RUTA PARA EL ADMIN - CLIENTES
        Permission::create(['name' => 'admin.clientes.index'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::create(['name' => 'admin.clientes.create'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::create(['name' => 'admin.clientes.store'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::create(['name' => 'admin.clientes.show'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::create(['name' => 'admin.clientes.edit'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::create(['name' => 'admin.clientes.update'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::create(['name' => 'admin.clientes.confirmDelete'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::create(['name' => 'admin.clientes.destroy'])->syncRoles([$admin,$secretaria,$nodo,$socio]);

        //RUTA PARA EL ADMIN - OPERACIONES
        Permission::create(['name' => 'admin.operaciones.index'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::create(['name' => 'admin.operaciones.create'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::create(['name' => 'admin.operaciones.store'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::create(['name' => 'admin.operaciones.show'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::create(['name' => 'admin.operaciones.edit'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::create(['name' => 'admin.operaciones.update'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::create(['name' => 'admin.operaciones.confirmDelete'])->syncRoles([$admin,$secretaria,$nodo,$socio]);
        Permission::create(['name' => 'admin.operaciones.destroy'])->syncRoles([$admin,$secretaria,$nodo,$socio]);


        $this->call([
            LocalidadesSeeder::class
        ]);

    }
}
