<?php

namespace Database\Seeders\Medico;

use App\Models\Medico\TipoHabitoToxico;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoHabitoToxicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoHabitoToxico::insert([
            ['nombre' => 'Tabaco'],
            ['nombre' => 'Alcohol'],
            ['nombre' => 'Otras Drogas'],
        ]);
    }
}

