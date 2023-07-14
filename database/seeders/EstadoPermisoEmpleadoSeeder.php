<?php

namespace Database\Seeders;

use App\Models\RecursosHumanos\NominaPrestamos\EstadoPermisoEmpleado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadoPermisoEmpleadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EstadoPermisoEmpleado::insert(
           [ ['nombre' => 'Aprobado'],
            ['nombre' => 'Rechazado'],
            ['nombre' => 'Anulado']]
        );
    }
}
