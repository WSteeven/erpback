<?php

namespace Database\Seeders;

use App\Models\Medico\ConfiguracionExamenCategoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfiguracionExamenCategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ConfiguracionExamenCategoria::insert([
            [
                'nombre' => 'BIOMETRIA',
                'examen_id' => 2,
            ],
            [
                'nombre' => 'FISICO',
                'examen_id' => 3,
            ],
            [
                'nombre' => 'QUIMICO',
                'examen_id' => 3,
            ],
            [
                'nombre' => 'MICROSCOPICO',
                'examen_id' => 3,
            ],
            [
                'nombre' => 'COPROPARASITARIO + COPROLOGICO',
                'examen_id' => 4,
            ],
            [
                'nombre' => 'QUIMICA SANGUINEA',
                'examen_id' => 5,
            ],
            [
                'nombre' => 'ENZIMAS',
                'examen_id' => 6,
            ],
        ]);
    }
}
