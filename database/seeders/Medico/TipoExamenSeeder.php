<?php

namespace Database\Seeders\RecursosHumanos\Medico;

// use App\Models\TipoExamen;

use App\Models\Medico\TipoExamen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoExamenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoExamen::insert([
            [
                'nombre' => 'COMUNES',
            ],
            [
                'nombre' => 'ESPECIALES',
            ],
        ]);
    }
}
