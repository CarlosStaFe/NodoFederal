<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SociosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('socios')->insert([
            'numero' => 1,
            'nodo_id' => 24, // Nodo General
            'clase' => 'SIMPLE',
            'razon_social' => 'SOCIO GENERAL',
            'domicilio' => '-',
            'cod_postal_id' => '1',
            'telefono' => '9',
            'email' => 'sociogeneral@mail.com',
            'cuit' => '20000000000',
            'tipo' => 'Exento',
            'observacion' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
