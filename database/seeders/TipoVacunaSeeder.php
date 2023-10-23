<?php

namespace Database\Seeders;

use App\Models\TipoVacuna;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoVacunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoVacuna::insert([
            [
                'nombre' => 'Covid',
                'dosis_totales' => 4,
            ],
            [
                'nombre' => 'Fiebre amarilla',
                'dosis_totales' => 1,
            ],
            [
                'nombre' => 'Difteria',
                'dosis_totales' => 3,
            ],
            [
                'nombre' => 'Hepatitis a o b, ab',
                'dosis_totales' => 1,
            ],
            [
                'nombre' => 'Tetanos',
                'dosis_totales' => 3,
            ],
            [
                'nombre' => 'Influenza',
                'dosis_totales' => 1,
            ],
        ]);
    }
}
