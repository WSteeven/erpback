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
            [18, 'GUANTES BLANCO,FILO AZUL', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, NULL, NULL, 2, 2, '2023-02-14 04:03:37', '2023-02-14 04:03:37'],
            [19, 'PARA PERSONAL NUEVO EN GRUPO BALSAS', NULL, NULL, NULL, NULL, 12, NULL, 8, NULL, NULL, NULL, NULL, 1, 5, 12, 12, 12, 2, 2, '2023-02-14 04:13:17', '2023-02-14 04:13:17'],
            [20, 'INGRESO', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, NULL, NULL, 2, 2, '2023-02-15 03:54:42', '2023-02-15 03:54:42'],
            [21, 'INGRESO', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, NULL, NULL, 2, 2, '2023-02-15 03:57:05', '2023-02-15 03:57:05'],
            [22, 'INGRESO', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, NULL, NULL, 2, 2, '2023-02-15 04:00:38', '2023-02-15 04:00:38'],
            [23, 'SUMINISTRO PARA DEPARTAMENTO CONTABLE', NULL, NULL, NULL, NULL, 12, NULL, 8, NULL, NULL, NULL, NULL, 1, 5, 12, 12, 12, 2, 2, '2023-02-15 04:06:37', '2023-02-15 04:06:37'],
            [24, 'INGRESO BODEGA', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, NULL, NULL, 2, 2, '2023-02-15 04:41:06', '2023-02-15 04:41:06'],
            [25, 'INGRESO DE CALCULADORA', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, NULL, NULL, 2, 2, '2023-02-15 10:38:17', '2023-02-15 10:38:17'],
            [26, 'SOLICITADOR POR JOEL BUSTOS, PARA PROYECTO PROCISA', NULL, NULL, NULL, NULL, 12, NULL, 8, NULL, NULL, NULL, NULL, 1, 5, 12, 12, 12, 2, 2, '2023-02-17 11:14:49', '2023-02-17 11:14:49'],
            [27, 'COMPRA', '001100000004236', NULL, NULL, NULL, 12, NULL, 2, NULL, NULL, NULL, NULL, 1, 5, 12, NULL, NULL, 2, 2, '2023-02-22 23:44:49', '2023-02-22 23:44:49'],
            [28, 'SOLICITADOR POR JOEL BUSTOS, PARA PROYECTO PROCISA', NULL, NULL, NULL, NULL, 12, 28, 8, NULL, NULL, NULL, NULL, 1, 5, 12, 12, 28, 2, 2, '2023-02-23 00:05:57', '2023-02-23 00:05:57'],
            [30, 'INGRESO DE PATCH', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, NULL, NULL, 2, 1, '2023-02-23 04:49:39', '2023-02-23 04:49:39'],
            [32, 'INGRESO DE CAJAS GRAPAS', '001001000000018', NULL, NULL, NULL, 12, NULL, 2, NULL, NULL, NULL, NULL, 1, 5, 12, NULL, NULL, 2, 2, '2023-02-24 05:06:50', '2023-02-24 05:06:50'],
            [33, 'COMPRA DE INSUMOS (GOMA)', '001001000000018', NULL, NULL, NULL, 12, NULL, 2, NULL, NULL, NULL, NULL, 1, 5, 12, NULL, NULL, 2, 2, '2023-02-24 05:21:42', '2023-02-24 05:21:42'],
            [34, 'COMPRA DE INSUMOS (SACAGRAPAS)', '0010010000000018', NULL, NULL, NULL, 12, NULL, 2, NULL, NULL, NULL, NULL, 1, 5, 12, NULL, NULL, 2, 2, '2023-02-24 05:25:34', '2023-02-24 05:25:34'],
            [35, 'COMPRA DE ARCHIVADORES', '0010010000000018', NULL, NULL, NULL, 12, NULL, 2, NULL, NULL, NULL, NULL, 1, 5, 12, NULL, NULL, 2, 2, '2023-02-24 05:27:37', '2023-02-24 05:27:37'],
            [36, 'INGRESO DE CORRECTOR', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, NULL, NULL, 2, 2, '2023-02-24 06:00:03', '2023-02-24 06:00:03'],
            [37, 'CINTA DE EMBALAJE INGRESO INICIAL', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, NULL, NULL, 2, 2, '2023-02-24 06:01:45', '2023-02-24 06:01:45'],
            [38, 'INGRESO INICIAL ESFEROS', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, NULL, NULL, 2, 2, '2023-02-24 06:04:05', '2023-02-24 06:04:05'],
            [42, 'STOCK DE TINTA NEGRA', NULL, NULL, NULL, NULL, 8, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 8, 8, NULL, 2, 2, '2023-02-24 22:01:45', '2023-02-24 22:01:45'],
            [43, 'PARA USO DEPARTAMENTO CONTABLE', NULL, NULL, NULL, NULL, 8, 13, 8, NULL, NULL, NULL, NULL, 1, 5, 8, 8, 13, 2, 2, '2023-02-24 22:02:33', '2023-02-24 22:02:33'],
            [44, 'STOCK INICIAL DE GAFAS', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 17:23:06', '2023-02-27 17:23:06'],
            [45, 'STOCK INICIAL DE CUERDA DE SUJECION', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 18:00:26', '2023-02-27 18:00:26'],
            [46, 'STOCK DE PANTALON DE HOMBRE', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 18:15:05', '2023-02-27 18:15:05'],
            [48, 'STOCK DE PANTALON DE HOMBRE', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 18:28:31', '2023-02-27 18:28:31'],
            [49, 'STOCK DE PANTALON DE HOMBRE', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 18:34:00', '2023-02-27 18:34:00'],
            [50, 'STOCK INICIAL DE PANTALONES', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 20:41:43', '2023-02-27 20:41:43'],
            [51, 'STOCK INCIAL DE PANTALONES', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 20:43:20', '2023-02-27 20:43:20'],
            [52, 'STOCK INCIAL DE PANTALONES', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 20:45:14', '2023-02-27 20:45:14'],
            [53, 'STOCK INCIAL DE PANTALONES', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 20:46:55', '2023-02-27 20:46:55'],
            [54, 'STOCK INCIAL DE PANTALONES', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 20:54:17', '2023-02-27 20:54:17'],
            [55, 'DE PRUEBA', NULL, NULL, NULL, NULL, 12, 12, 8, NULL, NULL, NULL, NULL, 1, 5, 12, 12, 12, 2, 2, '2023-02-27 21:10:51', '2023-02-27 21:10:51'],
            [56, 'PRUEBA', NULL, NULL, NULL, NULL, 12, 12, 8, NULL, NULL, NULL, NULL, 1, 5, 12, 12, 12, 2, 2, '2023-02-27 21:12:04', '2023-02-27 21:12:04'],
            [57, 'STOCK PANTALONES TALLA 30', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 21:13:47', '2023-02-27 21:13:47'],
            [58, 'STOCK DE PANTALONES USADOS', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 21:20:12', '2023-02-27 21:20:12'],
            [59, 'STOCK DE PANYALONES USADOS', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 21:21:40', '2023-02-27 21:21:40'],
            [60, 'STOCK DE PANTALONES', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 21:23:28', '2023-02-27 21:23:28'],
            [61, 'STOCK DE PANTALONES USADOS', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 21:25:36', '2023-02-27 21:25:36'],
            [62, 'STOCK DE PANTALONES USADOS', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 21:28:47', '2023-02-27 21:28:47'],
            [63, 'STOCK DE PANTALONES USADOS', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 21:30:28', '2023-02-27 21:30:28'],
            [64, 'STOCK DE PANTALONES SIN LOGO', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 21:33:43', '2023-02-27 21:33:43'],
            [65, 'STOCK DE PATALONES SIN LOGO', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 21:34:45', '2023-02-27 21:34:45'],
            [66, 'STOCK DE PANTALONES SIN LOGO', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 21:36:31', '2023-02-27 21:36:31'],
            [67, 'STOCK DE CASCOS BLANCOS', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 22:19:40', '2023-02-27 22:19:40'],
            [68, 'STOCK  BOTAS DIELECTRICAS', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 22:30:45', '2023-02-27 22:30:45'],
            [69, 'DESPACHO PARA BRIGGETHE DEPARTAMENTO GIS', NULL, NULL, NULL, NULL, 12, 12, 8, NULL, NULL, NULL, NULL, 1, 5, 12, 12, 12, 2, 2, '2023-02-27 22:32:52', '2023-02-27 22:32:52'],
            [70, 'COMPRA DE SUMINISTRO PARA EL DEPARTAMENTO GIS', '002001000147322', NULL, NULL, NULL, 27, NULL, 2, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 23:20:17', '2023-02-27 23:20:17'],
            [71, 'SUMINISTRO DE OFICINA, DEPARTAMENTO GIS', NULL, NULL, NULL, NULL, 12, 33, 8, NULL, NULL, NULL, NULL, 1, 5, 12, 12, 33, 2, 2, '2023-02-27 23:29:09', '2023-02-27 23:29:09'],
            [72, 'DEVOLUCION SUMINISTRO DEPARTAMENTO GIS', NULL, NULL, NULL, NULL, 12, NULL, 4, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 23:38:17', '2023-02-27 23:38:17'],
            [73, 'DESPACHO PARA EL DEPARTAMENTO GIS', NULL, NULL, NULL, NULL, 12, 34, 8, NULL, NULL, NULL, NULL, 1, 5, 12, 12, 34, 2, 2, '2023-02-27 23:39:18', '2023-02-27 23:39:18'],
            [74, 'CASCO PARA EL DEPARTAMETNO  GIS', NULL, NULL, NULL, NULL, 12, 34, 8, NULL, NULL, NULL, NULL, 1, 5, 12, 12, 34, 2, 2, '2023-02-27 23:41:23', '2023-02-27 23:41:23'],
            [75, 'STOCK DE BORRADOR', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 23:50:27', '2023-02-27 23:50:27'],
            [76, 'STOCK INCIAL LAPIZ', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-27 23:53:41', '2023-02-27 23:53:41'],
            [77, 'PARA TAREA DEL DEPARTAMENTO GIS', NULL, NULL, NULL, NULL, 12, 34, 8, NULL, NULL, NULL, NULL, 1, 5, 12, 12, 34, 2, 2, '2023-02-27 23:54:42', '2023-02-27 23:54:42'],
            [78, 'STOCK INCIAL DE GUANTES DIELECTRICOS', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-28 00:08:40', '2023-02-28 00:08:40'],
            [79, 'STOCK INCIAL DE BUZOS', NULL, NULL, NULL, NULL, 12, NULL, 6, NULL, NULL, NULL, NULL, 1, 5, 12, 12, NULL, 2, 2, '2023-02-28 17:01:22', '2023-02-28 17:01:22'],
        ];
        foreach ($datos as $fila) {
            DB::insert('INSERT INTO `transacciones_bodega` (`id`, `justificacion`, `comprobante`, `fecha_limite`, `observacion_aut`, `observacion_est`, `solicitante_id`, `responsable_id`, `motivo_id`, `tarea_id`, `devolucion_id`, `pedido_id`, `transferencia_id`, `sucursal_id`, `cliente_id`, `per_autoriza_id`, `per_atiende_id`, `per_retira_id`, `autorizacion_id`, `estado_id`, `created_at`, `updated_at`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', $fila);
            // DB::insert('INSERT INTO `transacciones_bodega` (`id`, `justificacion`, `fecha_limite`, `solicitante_id`, `subtipo_id`, `sucursal_id`, `per_autoriza_id`, `per_atiende_id`, `subtarea_id`, `created_at`, `updated_at`) VALUES(?,?,?,?,?,?,?,?,?,?,?)', $fila);
        }


        //Detalles
        $datos = [
            [23, 5, 18, 50, 0, '2023-02-14 04:03:37', '2023-02-14 04:03:37'],
            [24, 5, 19, 2, 0, '2023-02-14 04:13:17', '2023-02-14 04:13:17'],
            [25, 6, 20, 25, 0, '2023-02-15 03:54:43', '2023-02-15 03:54:43'],
            [26, 7, 21, 25, 0, '2023-02-15 03:57:05', '2023-02-15 03:57:05'],
            [27, 8, 22, 10, 0, '2023-02-15 04:00:38', '2023-02-15 04:00:38'],
            [28, 7, 23, 2, 0, '2023-02-15 04:06:37', '2023-02-15 04:06:37'],
            [29, 6, 23, 2, 0, '2023-02-15 04:06:37', '2023-02-15 04:06:37'],
            [30, 8, 23, 2, 0, '2023-02-15 04:06:37', '2023-02-15 04:06:37'],
            [31, 9, 24, 5, 0, '2023-02-15 04:41:06', '2023-02-15 04:41:06'],
            [32, 10, 25, 3, 0, '2023-02-15 10:38:18', '2023-02-15 10:38:18'],
            [33, 4, 26, 1, 0, '2023-02-17 11:14:50', '2023-02-17 11:14:50'],
            [34, 4, 27, 8, 0, '2023-02-22 23:44:51', '2023-02-22 23:44:51'],
            [35, 4, 28, 4, 0, '2023-02-23 00:05:57', '2023-02-23 00:05:57'],
            [37, 11, 30, 1, 0, '2023-02-23 04:49:40', '2023-02-23 04:49:40'],
            [39, 12, 32, 25, 0, '2023-02-24 05:06:50', '2023-02-24 05:06:50'],
            [40, 13, 33, 4, 0, '2023-02-24 05:21:42', '2023-02-24 05:21:42'],
            [41, 14, 34, 2, 0, '2023-02-24 05:25:34', '2023-02-24 05:25:34'],
            [42, 15, 35, 20, 0, '2023-02-24 05:27:37', '2023-02-24 05:27:37'],
            [43, 16, 36, 11, 0, '2023-02-24 06:00:03', '2023-02-24 06:00:03'],
            [44, 17, 37, 4, 0, '2023-02-24 06:01:45', '2023-02-24 06:01:45'],
            [51, 24, 42, 1, 0, '2023-02-24 22:01:45', '2023-02-24 22:01:45'],
            [52, 24, 43, 1, 0, '2023-02-24 22:02:33', '2023-02-24 22:02:33'],
            [53, 25, 44, 15, 0, '2023-02-27 17:23:07', '2023-02-27 17:23:07'],
            [54, 26, 45, 17, 0, '2023-02-27 18:00:26', '2023-02-27 18:00:26'],
            [55, 27, 46, 3, 0, '2023-02-27 18:15:05', '2023-02-27 18:15:05'],
            [57, 29, 48, 8, 0, '2023-02-27 18:28:31', '2023-02-27 18:28:31'],
            [58, 30, 49, 13, 0, '2023-02-27 18:34:00', '2023-02-27 18:34:00'],
            [59, 31, 50, 11, 0, '2023-02-27 20:41:44', '2023-02-27 20:41:44'],
            [60, 32, 51, 40, 0, '2023-02-27 20:43:20', '2023-02-27 20:43:20'],
            [61, 33, 52, 17, 0, '2023-02-27 20:45:14', '2023-02-27 20:45:14'],
            [62, 27, 53, 2, 0, '2023-02-27 20:46:55', '2023-02-27 20:46:55'],
            [63, 31, 54, 11, 0, '2023-02-27 20:54:17', '2023-02-27 20:54:17'],
            [64, 27, 55, 2, 0, '2023-02-27 21:10:51', '2023-02-27 21:10:51'],
            [65, 31, 56, 11, 0, '2023-02-27 21:12:04', '2023-02-27 21:12:04'],
            [66, 34, 57, 2, 0, '2023-02-27 21:13:47', '2023-02-27 21:13:47'],
            [67, 35, 58, 2, 0, '2023-02-27 21:20:12', '2023-02-27 21:20:12'],
            [68, 36, 59, 7, 0, '2023-02-27 21:21:40', '2023-02-27 21:21:40'],
            [69, 37, 60, 14, 0, '2023-02-27 21:23:28', '2023-02-27 21:23:28'],
            [70, 38, 61, 4, 0, '2023-02-27 21:25:36', '2023-02-27 21:25:36'],
            [71, 39, 62, 5, 0, '2023-02-27 21:28:47', '2023-02-27 21:28:47'],
            [72, 40, 63, 4, 0, '2023-02-27 21:30:28', '2023-02-27 21:30:28'],
            [73, 41, 64, 2, 0, '2023-02-27 21:33:43', '2023-02-27 21:33:43'],
            [74, 42, 65, 6, 0, '2023-02-27 21:34:45', '2023-02-27 21:34:45'],
            [75, 43, 66, 6, 0, '2023-02-27 21:36:31', '2023-02-27 21:36:31'],
            [76, 44, 67, 17, 0, '2023-02-27 22:19:40', '2023-02-27 22:19:40'],
            [77, 45, 68, 2, 0, '2023-02-27 22:30:45', '2023-02-27 22:30:45'],
            [78, 45, 69, 1, 0, '2023-02-27 22:32:52', '2023-02-27 22:32:52'],
            [79, 46, 70, 1, 0, '2023-02-27 23:20:17', '2023-02-27 23:20:17'],
            [80, 47, 70, 50, 0, '2023-02-27 23:20:17', '2023-02-27 23:20:17'],
            [81, 48, 70, 1, 0, '2023-02-27 23:20:17', '2023-02-27 23:20:17'],
            [82, 48, 71, 1, 0, '2023-02-27 23:29:09', '2023-02-27 23:29:09'],
            [83, 47, 71, 50, 0, '2023-02-27 23:29:09', '2023-02-27 23:29:09'],
            [84, 46, 71, 1, 0, '2023-02-27 23:29:09', '2023-02-27 23:29:09'],
            [85, 46, 72, 1, 0, '2023-02-27 23:38:17', '2023-02-27 23:38:17'],
            [86, 47, 72, 50, 0, '2023-02-27 23:38:17', '2023-02-27 23:38:17'],
            [87, 48, 72, 1, 0, '2023-02-27 23:38:17', '2023-02-27 23:38:17'],
            [88, 48, 73, 1, 0, '2023-02-27 23:39:18', '2023-02-27 23:39:18'],
            [89, 47, 73, 50, 0, '2023-02-27 23:39:18', '2023-02-27 23:39:18'],
            [90, 46, 73, 1, 0, '2023-02-27 23:39:18', '2023-02-27 23:39:18'],
            [91, 44, 74, 1, 0, '2023-02-27 23:41:23', '2023-02-27 23:41:23'],
            [92, 49, 75, 2, 0, '2023-02-27 23:50:27', '2023-02-27 23:50:27'],
            [93, 50, 76, 1, 0, '2023-02-27 23:53:41', '2023-02-27 23:53:41'],
            [94, 18, 77, 2, 0, '2023-02-27 23:54:42', '2023-02-27 23:54:42'],
            [95, 50, 77, 1, 0, '2023-02-27 23:54:42', '2023-02-27 23:54:42'],
            [96, 49, 77, 2, 0, '2023-02-27 23:54:42', '2023-02-27 23:54:42'],
            [97, 51, 78, 143, 0, '2023-02-28 00:08:40', '2023-02-28 00:08:40'],
            [98, 52, 79, 14, 0, '2023-02-28 17:01:22', '2023-02-28 17:01:22'],
            [99, 53, 79, 6, 0, '2023-02-28 17:01:22', '2023-02-28 17:01:22'],
            [100, 54, 79, 24, 0, '2023-02-28 17:01:22', '2023-02-28 17:01:22'],
            [101, 55, 79, 49, 0, '2023-02-28 17:01:22', '2023-02-28 17:01:22'],
            [102, 56, 79, 22, 0, '2023-02-28 17:01:22', '2023-02-28 17:01:22'],
        ];
        foreach ($datos as $fila) {
            DB::insert('INSERT INTO `detalle_producto_transaccion` (`id`, `detalle_id`, `transaccion_id`, `cantidad_inicial`, `cantidad_final`, `created_at`, `updated_at`) VALUES(?,?,?,?,?,?,?)', $fila);
        }


    }
}
