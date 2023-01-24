<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datos = [
            [1, 'TAREA #3 GRUPO JAIRO', NULL, NULL, NULL, 6, 2, 6, 2, 1, 1, '2023-01-24 15:52:46', '2023-01-24 15:52:46']
        ];

        foreach ($datos as $fila) {
            DB::insert('INSERT INTO `pedidos` (`id`, `justificacion`, `fecha_limite`, `observacion_aut`, `observacion_est`, `solicitante_id`, `autorizacion_id`, `per_autoriza_id`, `tarea_id`, `sucursal_id`, `estado_id`, `created_at`, `updated_at`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)', $fila);
        }


        $datos = [
            [1, 225, 1, 3, 0, '2023-01-24 15:52:46', '2023-01-24 15:52:46'],
            [2, 224, 1, 5, 0, '2023-01-24 15:52:46', '2023-01-24 15:52:46'],
        ];
        foreach ($datos as $fila) {
            DB::insert('INSERT INTO `detalle_pedido_producto` (`id`, `detalle_id`, `pedido_id`, `cantidad`, `despachado`, `created_at`, `updated_at`) VALUES(?,?,?,?,?,?,?)', $fila);
        }
    }
}
