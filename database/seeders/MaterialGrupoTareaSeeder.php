<?php

namespace Database\Seeders;

use App\Models\MaterialEmpleadoTarea;
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
        MaterialEmpleadoTarea::insert([
            [
                'cantidad_stock' => 100,
                'tarea_id' => 2,
                'responsable_id' => 14,
                'detalle_producto_id' => 37,
                'es_fibra' => false,
            ],
            [
                'cantidad_stock' => 80,
                'tarea_id' => 2,
                'responsable_id' => 14,
                'detalle_producto_id' => 45,
                'es_fibra' => false,
            ],
            [
                'cantidad_stock' => 74,
                'tarea_id' => 2,
                'responsable_id' => 14,
                'detalle_producto_id' => 31,
                'es_fibra' => false,
            ],
            [
                'cantidad_stock' => 88,
                'tarea_id' => 2,
                'responsable_id' => 1,
                'detalle_producto_id' => 23,
                'es_fibra' => false,
            ],
            [
                'cantidad_stock' => 52,
                'tarea_id' => 2,
                'responsable_id' => 2,
                'detalle_producto_id' => 19,
                'es_fibra' => false,
            ],
            [
                'cantidad_stock' => 63,
                'tarea_id' => 2,
                'responsable_id' => 2,
                'detalle_producto_id' => 37,
                'es_fibra' => false,
            ],
            // fibras
            [
                'cantidad_stock' => 1,
                'tarea_id' => 2,
                'responsable_id' => 1,
                'detalle_producto_id' => 16,
                'es_fibra' => true,
            ],
            [
                'cantidad_stock' => 1,
                'tarea_id' => 2,
                'responsable_id' => 1,
                'detalle_producto_id' => 17,
                'es_fibra' => true,
            ],
            // Tarea 1
            [
                'cantidad_stock' => 100,
                'tarea_id' => 1,
                'responsable_id' => 1,
                'detalle_producto_id' => 37,
                'es_fibra' => false,
            ],
            [
                'cantidad_stock' => 80,
                'tarea_id' => 1,
                'responsable_id' => 1,
                'detalle_producto_id' => 45,
                'es_fibra' => false,
            ],
            [
                'cantidad_stock' => 74,
                'tarea_id' => 1,
                'responsable_id' => 1,
                'detalle_producto_id' => 31,
                'es_fibra' => false,
            ],
            // fibras
            [
                'cantidad_stock' => 1,
                'tarea_id' => 1,
                'responsable_id' => 1,
                'detalle_producto_id' => 16,
                'es_fibra' => true,
            ],
        ]);
    }
}
