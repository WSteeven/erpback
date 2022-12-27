<?php

namespace Database\Seeders;

use App\Models\MaterialGrupoTarea;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaterialGrupoTareaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MaterialGrupoTarea::insert([
            [
                'cantidad_stock' => 100,
                'tarea_id' => 2,
                'grupo_id' => 1,
                'detalle_producto_id' => 37,
            ],
            [
                'cantidad_stock' => 80,
                'tarea_id' => 2,
                'grupo_id' => 1,
                'detalle_producto_id' => 45,
            ],
            [
                'cantidad_stock' => 74,
                'tarea_id' => 2,
                'grupo_id' => 1,
                'detalle_producto_id' => 31,
            ],
            [
                'cantidad_stock' => 88,
                'tarea_id' => 2,
                'grupo_id' => 1,
                'detalle_producto_id' => 23,
            ],
            [
                'cantidad_stock' => 52,
                'tarea_id' => 2,
                'grupo_id' => 2,
                'detalle_producto_id' => 19,
            ],
            [
                'cantidad_stock' => 63,
                'tarea_id' => 2,
                'grupo_id' => 2,
                'detalle_producto_id' => 37,
            ],
        ]);
    }
}
