<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MotivoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datos = [
            [1, 'VENTA', 2, '2022-11-25 01:48:57', '2022-11-25 01:48:57'],
            [2, 'COMPRA A PROVEEDOR', 1, '2022-11-25 01:49:39', '2022-11-25 01:49:39'],
            [3, 'EGRESO POR TRANSFERENCIA', 3, '2022-11-25 01:52:03', '2022-11-25 01:52:03'],
            [4, 'MERCADERIA DE CLIENTE PARA TAREA', 1, '2022-11-25 01:56:37', '2022-11-25 02:03:40'],
            [5, 'DEVOLUCION POR FINALIZACION LABORAL', 1, '2022-11-25 01:57:35', '2022-11-25 02:09:15'],
            [6, 'DEVOLUCION DE TAREA', 1, '2022-11-25 01:57:50', '2022-11-25 01:57:50'],
            [7, 'STOCK INICIAL', 1, '2022-11-25 01:58:02', '2022-11-25 01:58:02'],
            [8, 'DESPACHO DE TAREA', 2, '2022-11-25 01:58:38', '2022-11-25 01:58:38'],
            [9, 'DESPACHO', 2, '2022-11-25 01:58:48', '2022-11-25 01:58:48'],
            [10, 'DEVOLUCION AL PROVEEDOR', 2, '2022-11-25 01:59:06', '2022-11-25 01:59:06'],
            [11, 'REPOSICION', 2, '2022-11-25 01:59:19', '2022-11-25 01:59:19'],
            [12, 'TRANSFERENCIA ENTRE BODEGAS', 3, '2022-11-25 01:59:43', '2022-11-25 01:59:43'],
            [13, 'INGRESO POR LIQUIDACION DE MATERIALES', 1, '2022-11-25 02:00:34', '2022-11-25 02:00:34'],
            [14, 'EGRESO POR LIQUIDACION DE MATERIALES', 2, '2022-11-25 02:00:56', '2022-11-25 02:00:56'],
            [15, 'AJUSTE DE INGRESO POR REGULARIZACION', 1, '2022-11-25 02:02:19', '2022-11-25 02:02:19'],
            [16, 'AJUSTE DE EGRESO POR REGULARIZACION', 2, '2022-11-25 02:02:37', '2022-11-25 02:02:37'],
            [17, 'MERCADERIA DE CLIENTE PARA STOCK', 1, '2022-11-25 02:03:55', '2022-11-25 02:03:55'],
            [18, 'DEVOLUCION POR GARANTIA', 1, '2022-11-25 02:09:46', '2022-11-25 02:09:46'],
            [19, 'DEVOLUCION POR DAÑO', 1, '2022-11-25 02:10:14', '2022-11-25 02:10:14'],
            [20, 'DESPACHO POR GARANTIA', 2, '2022-11-25 02:12:19', '2022-11-25 02:12:19'],
        ];

        foreach($datos as $fila){
            DB::insert('INSERT INTO `motivos` (`id`, `nombre`, `tipo_transaccion_id`, `created_at`, `updated_at`) VALUES(?,?,?,?,?)',$fila);
        }
    }
}
