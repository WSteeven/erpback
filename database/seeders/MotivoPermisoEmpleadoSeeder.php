<?php

namespace Database\Seeders;

use App\Models\RecursosHumanos\NominaPrestamos\MotivoPermisoEmpleado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MotivoPermisoEmpleadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       MotivoPermisoEmpleado::insert([
        ['nombre'=>'Permiso General'],
        ['nombre'=>'Fuerza Mayor']]
    );
    }
}
