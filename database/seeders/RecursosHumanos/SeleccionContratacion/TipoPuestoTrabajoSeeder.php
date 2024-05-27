<?php

namespace Database\Seeders\RecursosHumanos\SeleccionContratacion;

use App\Models\RecursosHumanos\SeleccionContratacion\TipoPuestoTrabajo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoPuestoTrabajoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoPuestoTrabajo::insert([
            ['nombre' => 'NUEVO'],
            ['nombre' => 'VACANTE'],
            ['nombre' => 'PASANTE'],
        ]);
    }
}
