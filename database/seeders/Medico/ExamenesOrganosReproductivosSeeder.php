<?php

namespace Database\Seeders\Medico;

use App\Models\Medico\ExamenOrganoReproductivo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExamenesOrganosReproductivosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExamenOrganoReproductivo::create(['examen' => 'PAPANICOLAU', 'tipo' => 'F']);
        ExamenOrganoReproductivo::create(['examen' => 'COLPOSCOPIA', 'tipo' => 'F']);
        ExamenOrganoReproductivo::create(['examen' => 'ECO MAMARIO', 'tipo' => 'F']);
        ExamenOrganoReproductivo::create(['examen' => 'MAMOGRAFIA', 'tipo' => 'F']);
        ExamenOrganoReproductivo::create(['examen' => 'ANTIGENO PROSTATICO', 'tipo' => 'M']);
        ExamenOrganoReproductivo::create(['examen' => 'ECO PROSTATICO', 'tipo' => 'M']);
    }
}
