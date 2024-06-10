<?php

namespace Database\Seeders;

use App\Models\RecursosHumanos\NominaPrestamos\TipoLicencia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoLicenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $maternidad = TipoLicencia::insert([
            ['nombre'=>'Maternidad','num_dias'=>84],
            ['nombre'=>'Paternidad','num_dias'=>15],
            ['nombre'=>'Matrimonio civil del trabajador','num_dias'=>0],
            ['nombre'=>'Asistencia a evento de capacitación y/o entrenamiento','num_dias'=>0],
            ['nombre'=>'Tratamiento de enferemedades congenitas','num_dias'=>25],
            ['nombre'=>'Sufragio ','num_dias'=>1],
            ['nombre'=>'Fallecimiento de conyuge o conviviente registrado(a) del trabajador ','num_dias'=>3],
            ['nombre'=>'Licencia prevista en el codigo de trabajo','num_dias'=>0],
            ['nombre'=>'Nacimiento prematuro de hijo','num_dias'=>23],
            ['nombre'=>'Enferemedades degenerativa de hijo','num_dias'=>25],
            ['nombre'=>'Nacimiento multiples o cesaria','num_dias'=>20]

        ]);

    }
}
