<?php

namespace Database\Seeders\RecursosHumanos\Medico;

use App\Models\Medico\TipoVacuna;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoVacunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\Medico/TipoVacunaSeeder"
     * @return void
     */
    public function run()
    {
        TipoVacuna::insert([
            [
                'nombre' => 'COVID',
                'dosis_totales' => 4,
            ],
            [
                'nombre' => 'FIEBRE AMARILLA',
                'dosis_totales' => 1,
            ],
            [
                'nombre' => 'DIFTERIA/TÉTANOS',
                'dosis_totales' => 3,
            ],
            [
                'nombre' => 'HEPATITIS A',
                'dosis_totales' => 1,
            ],
            [
                'nombre' => 'HEPATITIS AB',
                'dosis_totales' => 1,
            ],
            [
                'nombre' => 'INFLUENZA',
                'dosis_totales' => 1,
            ],
        ]);
    }
}
