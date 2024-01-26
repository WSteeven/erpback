<?php

namespace Database\Seeders;

use App\Models\Medico\GestionPaciente;
use App\Models\Medico\RegistroEmpleadoExamen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegistroEmpleadoExamenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RegistroEmpleadoExamen::insert([
            [
                'numero_registro' => 1,
                'observacion' => 'PRIMER INGRESO',
                'tipo_proceso_examen' => RegistroEmpleadoExamen::INGRESO,
                'empleado_id' => 25,
            ]
        ]);
    }
}
