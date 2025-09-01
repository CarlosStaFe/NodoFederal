<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LocalidadesSeeder extends Seeder
{
    public function run()
    {
        // Ruta del archivo JSON
        $json = File::get(storage_path('app/localidades.json'));

        // Decodificar el JSON a un array
        $localidades = json_decode($json, true);

        // Dividir los datos en lotes de 500 registros
        $chunks = array_chunk($localidades, 500);

        foreach ($chunks as $chunk) {
            DB::table('localidades')->insert($chunk);
        }
    }
}

// php artisan db:seed --class=LocalidadesSeeder