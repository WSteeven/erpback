<?php

namespace Database\Seeders;

use App\Models\Ventas\BonoPorcentual;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BonosPorcentualesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BonoPorcentual::insert([
            ['porcentaje' => 110,'comision'=>25,'tipo_vendedor'=>'SUPERVISOR_VENTAS'],
            ['porcentaje' => 120,'comision'=>50,'tipo_vendedor'=>'SUPERVISOR_VENTAS'],
            ['porcentaje' => 130,'comision'=>75,'tipo_vendedor'=>'SUPERVISOR_VENTAS'],
        ]);
    }
}
