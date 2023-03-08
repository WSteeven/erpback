<?php

namespace Database\Seeders;

use App\Models\ControlMaterialTrabajo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ControlMaterialesSubtareasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ControlMaterialTrabajo::insert([
            [
                'stock_actual' => 80,
                'cantidad_utilizada' => 16,
                'fecha' => '30-12-2022',
                'tarea_id' => 1,
                'subtarea_id' => 2,
                'grupo_id' => 1,
                'detalle_producto_id' => 45,
            ],
            [
                'stock_actual' => 74,
                'cantidad_utilizada' => 4,
                'fecha' => '30-12-2022',
                'tarea_id' => 1,
                'subtarea_id' => 2,
                'grupo_id' => 1,
                'detalle_producto_id' => 31,
            ],
            [
                'stock_actual' => 64,
                'cantidad_utilizada' => 5,
                'fecha' => '30-12-2022',
                'tarea_id' => 1,
                'subtarea_id' => 2,
                'grupo_id' => 1,
                'detalle_producto_id' => 45,
            ],
            [
                'stock_actual' => 70,
                'cantidad_utilizada' => 3,
                'fecha' => '30-12-2022',
                'tarea_id' => 1,
                'subtarea_id' => 2,
                'grupo_id' => 1,
                'detalle_producto_id' => 31,
            ],
        ]);
    }
}
