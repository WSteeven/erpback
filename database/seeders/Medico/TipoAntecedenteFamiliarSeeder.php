<?php

namespace Database\Seeders\Medico;

use App\Models\Medico\TipoAntecedenteFamiliar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoAntecedenteFamiliarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       TipoAntecedenteFamiliar::insert([
        ['nombre'=>'Enfermedad cardio-vascular'],
        ['nombre'=>'Enfermedad metabólica'],
        ['nombre'=>'Enfermedad neurológica'],
        ['nombre'=>'Oncológica'],
        ['nombre'=>'Enfermedad infeciosa'],
        ['nombre'=>'Enfermedad hereditaria/congénita'],
        ['nombre'=>'Discapacidades'],
    ]);
    }
}
