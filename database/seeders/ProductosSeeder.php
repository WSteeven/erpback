<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductosSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        Nombres de productos
         */
        /* 
        Categoria::create(['nombre'=>'SUMINISTROS']);   1
        Categoria::create(['nombre'=>'MATERIALES']);    2
        Categoria::create(['nombre'=>'INFORMATICA']);   3
        Categoria::create(['nombre'=>'HERRAMIENTAS']);  4
        Categoria::create(['nombre'=>'EQUIPOS']);       5
        Categoria::create(['nombre'=>'EPP']);           6
         */

        $datos = [
            [1, 'LAPTOP', 6, 1, '2023-02-07 10:43:41', '2023-02-07 10:43:41'],
            [3, 'TABLET', 6, 1, '2023-02-07 23:31:14', '2023-02-07 23:31:14'],
            [4, 'MOUSE', 1, 1, '2023-02-07 23:50:16', '2023-02-07 23:51:58'],
            [5, 'IMPRESORA DE ETIQUETAS', 3, 1, '2023-02-08 00:02:40', '2023-02-08 00:02:40'],
            [6, 'TELEFONO FIJO', 3, 1, '2023-02-08 03:13:46', '2023-02-08 03:13:46'],
            [7, 'CINTA PARA ETIQUETADORA', 8, 1, '2023-02-08 04:33:51', '2023-02-08 04:33:51'],
            [8, 'SILLA CORPORATIVA, GIRATORIA SENCILLA', 1, 1, '2023-02-08 21:49:44', '2023-02-08 21:49:44'],
            [9, 'GUANTES DE CUERO', 2, 1, '2023-02-09 04:27:32', '2023-02-09 04:27:32'],
            [10, 'CAJA DE CLIPS', 8, 1, '2023-02-14 21:50:22', '2023-02-14 21:51:58'],
            [11, 'CLIP MARIPOSA', 8, 1, '2023-02-14 21:55:58', '2023-02-14 21:55:58'],
            [12, 'RESMA DE HOJAS', 8, 1, '2023-02-14 21:59:03', '2023-02-14 21:59:03'],
            [13, 'GRAPADORA', 8, 1, '2023-02-14 22:35:45', '2023-02-14 22:35:45'],
            [14, 'CALCULADORA', 8, 1, '2023-02-15 02:55:32', '2023-02-15 02:55:32'],
            [15, 'PATCH E-2000/ APC-FC/APC', 7, 1, '2023-02-22 21:38:52', '2023-02-22 21:38:52'],
            [16, 'CAJAS DE GRAPAS', 8, 1, '2023-02-23 23:04:44', '2023-02-23 23:04:44'],
            [17, 'GOMA EN BARRA', 8, 1, '2023-02-23 23:16:42', '2023-02-23 23:16:42'],
            [18, 'SACAGRAPAS', 8, 1, '2023-02-23 23:24:02', '2023-02-23 23:24:02'],
            [19, 'ARCHIVADORES', 8, 1, '2023-02-23 23:25:57', '2023-02-23 23:25:57'],
            [20, 'CORRECTOR', 8, 1, '2023-02-23 23:58:37', '2023-02-23 23:58:37'],
            [21, 'CINTA DE EMBALAJE', 8, 1, '2023-02-24 00:00:39', '2023-02-24 00:00:39'],
            [22, 'ESFERO AZUL', 8, 1, '2023-02-24 00:02:54', '2023-02-24 00:02:54'],
            [23, 'CAMISA CUELLO', 2, 1, '2023-02-27 15:41:47', '2023-02-27 15:41:47'],
            [24, 'PANTALON REFLECTIVO', 2, 1, '2023-02-27 15:42:06', '2023-02-27 15:42:06'],
            [25, 'CASCO SEGURIDAD', 2, 1, '2023-02-27 15:42:15', '2023-02-27 15:42:15'],
            [26, 'ZAPATOS DIELECTRICOS', 2, 1, '2023-02-27 15:42:31', '2023-02-27 15:42:31'],
            [27, 'FO 24H', 2, 1, '2023-02-27 15:43:11', '2023-02-27 15:43:11'],

        ];

        foreach ($datos as $fila) {
            DB::insert('INSERT INTO productos (id, nombre, categoria_id, unidad_medida_id, created_at, updated_at) VALUES(?,?,?,?,?,?)', $fila);
        }
    }
}
