<?php

namespace Database\Seeders\RecursosHumanos\SeleccionContratacion;

use App\Models\RecursosHumanos\SeleccionContratacion\TipoPuesto;
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
        TipoPuesto::upsert([
            ['nombre' => 'NUEVO'],
            ['nombre' => 'VACANTE'],
            ['nombre' => 'PASANTE'],
        ], uniqueBy:['id'], update:['nombre']);
    }
}
