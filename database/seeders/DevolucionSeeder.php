<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DevolucionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //devoluciones
        $datos =[
            [1, 'devolucion de tablet usada', 12, NULL, 1, NULL, 'CREADA', '2023-02-24 16:51:28', '2023-02-24 16:51:28']
        ];
        foreach ($datos as $fila) {
            DB::insert('INSERT INTO `devoluciones` (`id`, `justificacion`, `solicitante_id`, `tarea_id`, `sucursal_id`, `causa_anulacion`, `estado`, `created_at`, `updated_at`) VALUES(?,?,?,?,?,?,?,?,?)', $fila);
            // DB::insert('INSERT INTO `transacciones_bodega` (`id`, `justificacion`, `fecha_limite`, `solicitante_id`, `subtipo_id`, `sucursal_id`, `per_autoriza_id`, `per_atiende_id`, `subtarea_id`, `created_at`, `updated_at`) VALUES(?,?,?,?,?,?,?,?,?,?,?)', $fila);
        }

        //detalles
        $datos = [
            [1, 15, 1, 1, '2023-02-24 16:51:28', '2023-02-24 16:51:28']
        ];
        foreach ($datos as $fila) {
            DB::insert('INSERT INTO `detalle_devolucion_producto` (`id`, `detalle_id`, `devolucion_id`, `cantidad`, `created_at`, `updated_at`) VALUES(?,?,?,?,?,?)', $fila);
        }
    }
}
