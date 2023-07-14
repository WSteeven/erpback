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
            ['nombre'=>'Maternidad y paternidad'],
            ['nombre'=>'Mattrimonio civil del trabajador'],
            ['nombre'=>'Asistencia a evento de capacitación y/o entrenamiento'],
            ['nombre'=>'Calamidad  domestica '],
            ['nombre'=>'Sufrajio '],
            ['nombre'=>'Fallecimiento de conyuge o conviviente registrado(a) del trabajador '],
            ['nombre'=>'Licencia prevista en el codigo de trabajo']
        ]);

    }
}
