<?php

namespace Database\Seeders\RecursosHumanos\Medico;

use App\Models\Medico\TipoAntecedente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoAntecedenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoAntecedente::insert([
            ['nombre' => 'Antígeno prostático', 'genero' => 'M'],
            ['nombre' => 'Eco prostático', 'genero' => 'M'],
            ['nombre' => 'Papanicolaou', 'genero' => 'F'],
            ['nombre' => 'Colposcopia', 'genero' => 'F'],
            ['nombre' => 'Eco mamario', 'genero' => 'F'],
            ['nombre' => 'Mamografía', 'genero' => 'F'],
        ]);
    }
}
