<?php

namespace Database\Seeders;

use App\Models\MotivoPermisoEmpleado;
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
        ['nombre'=>'Permiso medico IESS'],
        ['nombre'=>'Permiso medico Ocupacional'],
        ['nombre'=>'Permiso con cargo a vacaciones'],
        ['nombre'=>'Permiso con horas a recuperar'],
        ['nombre'=>'Memorandum -Sancion Pecuniaria'],
        ['nombre'=>'Calamidad Domestica'],
        ['nombre'=>'Licencia por maternidad'],
        ['nombre'=>'Vacación 2018-2019'],
        ['nombre'=>'Vacación 2019-2020'],
        ['nombre'=>'Vacación 2020-2021'],
        ['nombre'=>'Vacación 2021-2022'],]
    );
    }
}
