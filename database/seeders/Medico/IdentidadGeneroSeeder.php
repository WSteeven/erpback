<?php

namespace Database\Seeders\Medico;

use App\Models\Medico\IdentidadGenero;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IdentidadGeneroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IdentidadGenero::insert([
            ['nombre' => 'Femenino'],
            ['nombre' => 'Masculino'],
            ['nombre' => 'Trans-masculino'],
            ['nombre' => 'Trans-femenino'],
            ['nombre' => 'No sabe'],
        ]);
    }
}
