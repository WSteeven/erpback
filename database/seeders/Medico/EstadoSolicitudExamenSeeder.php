<?php

namespace Database\Seeders\Medico;

use App\Models\Medico\EstadoSolicitudExamen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadoSolicitudExamenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EstadoSolicitudExamen::insert([
            [
                'registro_empleado_examen_id' => 1,
                'examen_id' => 2,
                'estado_examen_id' =>1
            ]
        ]);
    }
}
