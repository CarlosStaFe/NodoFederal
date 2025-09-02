<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class NodosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ruta del archivo JSON
        $json = File::get(storage_path('app/nodos.json'));

        // Decodificar el JSON a un array
        $nodos = json_decode($json, true);

        // Dividir los datos en lotes de 500 registros
        $chunks = array_chunk($nodos, 500);

        foreach ($chunks as $chunk) {
            DB::table('nodos')->insert($chunk);
        }
    }
}
