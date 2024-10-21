<?php

namespace Database\Seeders\RecursosHumanos\Medico;

use App\Models\Medico\TipoAptitudMedicaLaboral;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoAptitudMedicaLaboralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\Medico\TipoAptitudMedicaLaboralSeeder"
     * @return void
     */
    public function run()
    {
        TipoAptitudMedicaLaboral::insert([
            [
                'nombre' => 'Apto',
            ],
            [
                'nombre' => 'Apto en observación',
            ],
            [
                'nombre' => 'Apto con limitaciones',
            ],
            [
                'nombre' => 'No apto'
            ]
        ]);
    }
}
