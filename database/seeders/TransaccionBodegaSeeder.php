<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransaccionBodegaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Transacciones
        $datos = [
            [1, 'QQQQQ', '2022-10-10', 14, 10, 1, 6, NULL, 1, '2022-10-26 22:50:13', '2022-10-26 22:50:13'],
            [2, 'QQQ', NULL, 14, 11, 1, 6, NULL, 2, '2022-10-26 23:00:29', '2022-10-26 23:00:29'],
            [3, 'AAAA', '2022-10-27', 14, 11, 1, 6, NULL, 1, '2022-10-26 23:01:11', '2022-10-26 23:01:11'],
            [4, 'ZZZ', '2022-10-30', 13, 11, 1, 3, NULL, 1, '2022-10-27 15:35:36', '2022-10-27 15:35:36'],
        ];
        foreach ($datos as $fila) {
            DB::insert('INSERT INTO `transacciones_bodega` (`id`, `justificacion`, `fecha_limite`, `solicitante_id`, `subtipo_id`, `sucursal_id`, `per_autoriza_id`, `per_atiende_id`, `subtarea_id`, `created_at`, `updated_at`) VALUES(?,?,?,?,?,?,?,?,?,?,?)', $fila);
        }


        //Detalles
        $datos = [
            [2, 20, 2, 12, 0, '2022-10-26 23:00:29', '2022-10-26 23:00:29'],
            [3, 1, 3, 1, 0, '2022-10-26 23:01:11', '2022-10-26 23:01:11'],
            [5, 19, 4, 1000, 0, '2022-10-27 15:37:41', '2022-10-27 15:37:41'],
            [6, 1, 1, 1, 0, '2022-10-27 16:24:28', '2022-10-27 16:24:28'],
        ];
        foreach ($datos as $fila) {
            DB::insert('INSERT INTO `detalle_productos_transacciones` (`id`, `detalle_id`, `transaccion_id`, `cantidad_inicial`, `cantidad_final`, `created_at`, `updated_at`) VALUES(?,?,?,?,?,?,?)', $fila);
        }

        //tiempos autorizaciones
        $datos = [
            [1, 1, 1, NULL, '2022-10-26 22:50:13', '2022-10-26 22:50:13'],
            [2, 1, 2, NULL, '2022-10-26 23:00:29', '2022-10-26 23:00:29'],
            [3, 1, 3, NULL, '2022-10-26 23:01:11', '2022-10-26 23:01:11'],
            [4, 1, 4, NULL, '2022-10-27 15:35:36', '2022-10-27 15:35:36'],
            [5, 2, 4, NULL, '2022-10-27 15:37:40', '2022-10-27 15:37:40'],
            [6, 3, 1, NULL, '2022-10-27 16:24:28', '2022-10-27 16:24:28'],
        ];
        foreach ($datos as $fila) {
            DB::insert('INSERT INTO `tiempo_autorizacion_transaccion` (`id`, `autorizacion_id`, `transaccion_id`, `observacion`, `created_at`, `updated_at`) VALUES(?,?,?,?,?,?)', $fila);
        }

        //tiempos estados
        $datos = [
            [1, 2, 1, NULL, '2022-10-26 22:50:13', '2022-10-26 22:50:13'],
            [2, 2, 2, NULL, '2022-10-26 23:00:29', '2022-10-26 23:00:29'],
            [3, 2, 3, NULL, '2022-10-26 23:01:11', '2022-10-26 23:01:11'],
            [4, 2, 4, NULL, '2022-10-27 15:35:36', '2022-10-27 15:35:36'],
        ];
        foreach ($datos as $fila) {
            DB::insert('INSERT INTO `tiempo_estado_transaccion` (`id`, `estado_id`, `transaccion_id`, `observacion`, `created_at`, `updated_at`) VALUES(?,?,?,?,?,?)', $fila);
        }
    }
}
