<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class ProductosClaroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fechaActual = Date::now()->format('Y-m-d');
        $datos = [
            ['2 PLAY - Esencial Plus 50 Mbps HFC + Telefonia Sin Limites  JUL23', '100086831', 24.00, 2, $fechaActual, $fechaActual],
            ['2 PLAY - Hiperconectado Plus 100 Mbps HFC + HBO + Telefonia Sin Limites  JUL23', '100086833', 29.00, 2, $fechaActual, $fechaActual],
            ['2 PLAY - Esencial Plus HD 50 Mbps HFC + Tv Super JUL23', '100086815', 29.00, 2, $fechaActual, $fechaActual],
            ['2 PLAY - Hiperconectado Plus HD 100 Mbps HFC + HBO MAX + Tv Super JUL23', '100086849', 34.00, 2, $fechaActual, $fechaActual],
            [' 3 PLAY - Esencial Pro 50 Mbps HFC + Telefonia Ilimitado + Tv Extra JUL23', '100087929', 30.00, 3, $fechaActual, $fechaActual],
            ['3 PLAY - Super Conectado Pro 75 Mbps HFC + Telefonia Sin Limites + Tv Super JUL23', '100088204', 35.00, 3, $fechaActual, $fechaActual],
            ['3 PLAY - Hiperconectado Pro 100 Mbps HFC + HBO MAX + Telefonia Sin Limites + Tv Maximo JUL23', '100088250', 43.00, 3, $fechaActual, $fechaActual],
            ['2 PLAY - Esencial Plus HD 50 Mbps HFC + Tv Super IPTV JUL23', '100086817', 29.00, 2, $fechaActual, $fechaActual],
            ['2 PLAY - Hiperconectado Plus HD 100 Mbps HFC + HBO MAX + Tv Super IPTV JUL23', '100086854', 34.00, 2, $fechaActual, $fechaActual],
            [' 3 PLAY - Esencial Pro 50 Mbps HFC + Telefonia Ilimitado + Tv Extra IPTV JUL23', '100087168', 30.00, 3, $fechaActual, $fechaActual],
            ['3 PLAY - Super Conectado Pro 75 Mbps HFC + Telefonia Sin Limites + Tv Super IPTV JUL23', '100088032', 35.00, 3, $fechaActual, $fechaActual],
            ['3 PLAY - Hiperconectado Pro 100 Mbps HFC + HBO MAX + Telefonia Sin Limites + Tv Maximo IPTV JUL23', '100088248', 43.00, 3, $fechaActual, $fechaActual],
            ['2 PLAY - Esencial Plus 50 Mbps GPON + Telefonia Sin Limites  JUL23', '100086797', 24.00, 2, $fechaActual, $fechaActual],
            ['2 PLAY - Hiperconectado Plus 100 Mbps GPON + HBO + Telefonia Sin Limites  JUL23', '100086800', 29.00, 2, $fechaActual, $fechaActual],
            ['2 PLAY - Esencial Plus HD 50 Mbps GPON + Tv Super Nueva Tv JUL23', '100086839', 29.00, 2, $fechaActual, $fechaActual],
            ['2 PLAY - Hiperconectado Plus HD 100 Mbps GPON + HBO MAX + Tv Super IPTV JUL23', '100086804', 34.00, 2, $fechaActual, $fechaActual],
            [' 3 PLAY - Esencial Pro 50 Mbps GPON + Telefonia Ilimitado + Tv Extra IPTV JUL23', '100086911', 30.00, 3, $fechaActual, $fechaActual],
            ['3 PLAY - Super Conectado Pro 75 Mbps GPON+ Telefonia Sin Limites + Tv Super IPTV JUL23', '100088270', 35.00, 3, $fechaActual, $fechaActual],
            ['3 PLAY - Hiperconectado Pro 100 Mbps GPON + HBO MAX +Telefonia Sin Limites + Tv Maximo  IPTV JUL23', '100086801', 43.00, 3, $fechaActual, $fechaActual],
            ['2 PLAY - Esencial Plus HD 50 Mbps GPON + Tv Super OTT JUL23', '100086884', 29.00, 2, $fechaActual, $fechaActual],
            ['2 PLAY - Hiperconectado Plus HD 100 Mbps GPON + HBO MAX + Tv Super OTT JUL23', '100086882', 34.00, 2, $fechaActual, $fechaActual],
            ['3 PLAY - Esencial Pro 50 Mbps GPON + Telefonia Ilimitado + Tv Extra OTT JUL23', '100088266', 30.00, 3, $fechaActual, $fechaActual],
            ['3 PLAY - Super Conectado Pro 75 Mbps GPON + Telefonia Sin Limites + Tv Super OTT JUL23', '100088268', 35.00, 3, $fechaActual, $fechaActual],
            ['3 PLAY - Hiperconectado Pro 100 Mbps GPON + HBO MAX +Telefonia Sin Limites + Tv Maximo  OTT JUL23', '100086809', 43.00, 3, $fechaActual, $fechaActual],
            ['2PLAY - TODO CLARO Esencial Plus 50 Mbps HFC Promo100 Mbps + Sin Limites', '100087587', 19.00, 2, $fechaActual, $fechaActual],
            ['2PLAY - TODO CLARO Hiperconectado Plus 100 Mbps HFC Promo250 Mbps + HBO Max+  Sin Limites', '100087722', 21.00, 2, $fechaActual, $fechaActual],
            ['2PLAY - TODO CLARO Esencial Plus HD 50 Mbps HFC promo100 Mbps + Super TV HFC', '100087724', 24.00, 2, $fechaActual, $fechaActual],
            ['2 PLAY - TODO CLARO Hiperconectado HD 100 Mbps HFC Promo250 Mbps HBO Max + Super TV HFC', '100087357', 26.00, 2, $fechaActual, $fechaActual],
            ['3PLAY - TODO CLARO Essencial Pro 50 Mbps HFC Promo 100Mbps + Hogar Ilimitado + TV Extra HFC', '100087726', 25.00, 3, $fechaActual, $fechaActual],
            ['3PLAY - TODO CLARO Super Conectado Pro 75 Mbps HFC Promo 150Mbps + Hogar Sin Limites + TV Super HFC', '100087772', 29.00, 3, $fechaActual, $fechaActual],
            ['3P-TODOCLARO Hiperconectado Pro 100 Mbps HFC Promo 250Mbps+HBO MAX +Hogar Sin Limites+TV Maximo', '100087814', 35.00, 3, $fechaActual, $fechaActual],
            ['2 PLAY - TODO CLARO Esencial Plus HD 50 Mbps HFC promo100 Mbps + Super Nueva TV HFC', '100087512', 24.00, 2, $fechaActual, $fechaActual],
            ['2 PLAY - TODO CLARO Hiperconectado Plus HD 100 Mbps HFC Promo250 Mbps + HBO MAX + Super HFC Nueva TV', '100087353', 26.00, 2, $fechaActual, $fechaActual],
            ['3PLAY - TODO CLARO Esencial Pro 50 Mbps HFC Promo 100Mbps + Hogar Ilimitado + TV Extra HFC IPTV', '100087711', 25.00, 3, $fechaActual, $fechaActual],
            ['3P-TODOCLARO Super Conectado Pro 75 Mbps HFC Promo 150Mbps + Hogar Sin Limites + TV Super IPTV', '100087817', 29.00, 3, $fechaActual, $fechaActual],
            ['3P-TODOCLARO Hiperconectado Pro 100 Mbps HFC Promo 250Mbps+HBO MAX+Hogar Sin Limites+TV Maximo IPTV', '100087819', 35.00, 3, $fechaActual, $fechaActual],
            ['2PLAY - TODO CLARO Esencial Plus 50 Mbps GPON Promo100 Mbps + Sin Limites', '100087262', 19.00, 2, $fechaActual, $fechaActual],
            ['2PLAY - TODO CLARO Hiperconectado Plus 100 Mbps GPON Promo250 Mbps + HBO MAX +  Sin Limites', '100087343', 21.00, 2, $fechaActual, $fechaActual],
            ['2PLAY - TODO CLARO Esencial Plus HD 50 Mbps GPON promo100 Mbps + Super NUEVA TV GPON', '100087346', 24.00, 2, $fechaActual, $fechaActual],
            ['2PLAY - TODO CLARO Hiperconectado HD 100 Mbps GPON Promo250 Mbps + HBO MAX + Super Nueva TV GPON', '100087518', 26.00, 2, $fechaActual, $fechaActual],
            ['3PLAY - TODO CLARO Essencial Pro 50 Mbps GPON Promo 100Mbps + Hogar Ilimitado + TV Extra GPON', '100087971', 25.00, 3, $fechaActual, $fechaActual],
            ['3P-TODOCLARO Super Conectado Pro 75 Mbps GPON Promo 150Mbps + Hogar Sin Limites + TV Super', '100087974', 29.00, 3, $fechaActual, $fechaActual],
            ['3P-TODOCLARO HiperconectadoPro 100 Mbps GPON Promo 250Mbps + HBO MAX + Hogar Sin Limites + TV Maximo', '100087831', 35.00, 3, $fechaActual, $fechaActual],
            ['2PLAY - TODO CLARO Esencial Plus HD 50 Mbps GPON promo100 Mbps + Super OTT GPON', '100087376', 24.00, 2, $fechaActual, $fechaActual],
            ['2PLAY - TODO CLARO Hiperconectado Plus HD 100 Mbps GPON Promo250 Mbps + HBO MAX + Super GPON OTT TV', '100087515', 26.00, 2, $fechaActual, $fechaActual],
            ['3PLAY - TODO CLARO Esencial Pro 50 Mbps GPON Promo 100Mbps + Hogar Ilimitado + TV Extra OTT GPON', '100087990', 25.00, 3, $fechaActual, $fechaActual],
            ['3P-TODOCLARO Super Conectado Pro 75 Mbps GPON Promo 150Mbps + Hogar Sin Limites + TV Super OTT', '100087940', 29.00, 3, $fechaActual, $fechaActual],
            ['3P-TODOCLARO Hiperconectado Pro 100 Mbps GPON Promo 250Mbps+HBO MAX+Hogar Sin Limites+TV Maximo OTT', '100087702', 35.00, 3, $fechaActual, $fechaActual],
            ['2PLAY - CAMBIATE Esencial Plus 50 Mbps HFC Promo100 Mbps + Sin Limites', '100087646', 19.00, 2, $fechaActual, $fechaActual],
            ['2PLAY - CAMBIATE Hiperconectado Plus 100 Mbps HFC Promo250 Mbps + HBO MAX +  Sin Limites', '100087638', 21.00, 2, $fechaActual, $fechaActual],
            ['2PLAY - Cambiate Esencial Plus HD 50 Mbps HFC promo100 Mbps + Super TV HFC', '100087681', 24.00, 2, $fechaActual, $fechaActual],
            ['2 PLAY - CAMBIATE Hiperconectado HD 100 Mbps HFC Promo250 Mbps HBO MAX + Super TV HFC', '100087580', 26.00, 2, $fechaActual, $fechaActual],
            ['3PLAY - Cambiate Essencial Pro 50 Mbps HFC Promo 100Mbps + Hogar Ilimitado + TV Extra HFC', '100087696', 25.00, 3, $fechaActual, $fechaActual],
            ['3PLAY - Cambiate Super Conectado Pro 75 Mbps HFC Promo 150Mbps + Hogar Sin Limites + TV Super HFC', '100087797', 29.00, 3, $fechaActual, $fechaActual],
            ['3P - CAMBIATE Hiperconectado Pro 100 Mbps HFC Promo250Mbps+HBO MAX+ Hogar Sin Limites+TV Maximo HFC', '100087596', 35.00, 3, $fechaActual, $fechaActual],
            ['2 PLAY - CAMBIATE Esencial Plus HD 50 Mbps HFC promo100 Mbps + Super Nueva TV HFC', '100087682', 24.00, 2, $fechaActual, $fechaActual],
            ['2 PLAY - CAMBIATE Hiperconectado Plus HD 100 Mbps HFC Promo250 Mbps + HBO MAX + Super HFC Nueva TV', '100087577', 26.00, 2, $fechaActual, $fechaActual],
            ['3PLAY - CAMBIATE Esencial Pro 50 Mbps HFC Promo 100Mbps + Hogar Ilimitado + TV Extra HFC IPTV', '100087674', 25.00, 3, $fechaActual, $fechaActual],
            ['3P- CAMBIATE Super Conectado Pro 75 Mbps HFC Promo 150Mbps + Hogar Sin Limites + TV Super HFC IPTV', '100087951', 29.00, 3, $fechaActual, $fechaActual],
            ['3P- CAMBIATE HiperconectadoPro100 Mbps HFC Promo 250Mbps+HBOMAX+Hogar Sin Limites+TV Maximo HFC IPTV', '100087776', 35.00, 3, $fechaActual, $fechaActual],
            ['2PLAY - CAMBIATE Esencial Plus 50 Mbps GPON Promo100 Mbps + Sin Limites', '100087629', 19.00, 2, $fechaActual, $fechaActual],
            ['2PLAY - CAMBIATE Hiperconectado Plus 100 Mbps GPON Promo250 Mbps + HBO MAX +  Sin Limites', '100087549', 21.00, 2, $fechaActual, $fechaActual],
            ['2PLAY - CAMBIATE Esencial Plus HD 50 Mbps GPON promo100 Mbps + Super NUEVA TV GPON', '100087555', 24.00, 2, $fechaActual, $fechaActual],
            ['2PLAY - CAMBIATE Hiperconectado HD 100 Mbps GPON Promo250 Mbps + HBO MAX + Super Nueva TV GPON', '100087497', 26.00, 2, $fechaActual, $fechaActual],
            ['3PLAY - CAMBIATE Essencial Pro 50 Mbps GPON Promo 100Mbps + Hogar Ilimitado + TV Extra GPON', '100087698', 25.00, 3, $fechaActual, $fechaActual],
            ['3PLAY - Cambiate Super Conectado Pro 75 Mbps GPON Promo 150Mbps + Hogar Sin Limites + TV Super GPON', '100088025', 29.00, 3, $fechaActual, $fechaActual],
            ['3P - CAMBIATE HiperconectadoPro 100Mbps Promo 250Mbps+HBO MAX+Hogar Sin Limites+TV Maximo GPON', '100087847', 35.00, 3, $fechaActual, $fechaActual],
            ['2PLAY - CAMBIATE Esencial Plus HD 50 Mbps GPON promo100 Mbps + Super OTT GPON', '100087557', 24.00, 2, $fechaActual, $fechaActual],
            ['2PLAY - CAMBIATE Hiperconectado Plus HD 100 Mbps GPON Promo250 Mbps + HBO MAX + Super GPON OTT TV', '100087499', 26.00, 2, $fechaActual, $fechaActual],
            ['3PLAY - CAMBIATE Esencial Pro 50 Mbps GPON Promo 100Mbps + Hogar Ilimitado + TV Extra OTT GPON', '100087829', 25.00, 3, $fechaActual, $fechaActual],
            ['3P - CAMBIATE Super Conectado Pro 75 Mbps GPON Promo 150Mbps + Hogar Sin Limites + TV Super OTT GPON', '100088026', 29.00, 3, $fechaActual, $fechaActual],
            ['3P- CAMBIATE HiperconectadoPro 100 Mbps Promo 250Mbps+HBO MAX+Hogar Sin Limites+TV Maximo OTT GPON', '100087848', 35.00, 3, $fechaActual, $fechaActual],
            ['1 PLAY - Esencial 50 Mbps GPON JUL23', '100086600', 20.00, 1, $fechaActual, $fechaActual],
            ['1 PLAY - Super Conectado 75 Mbps GPON JUL23', '100086647', 22.00, 1, $fechaActual, $fechaActual],
            ['1 PLAY - Hiperconectado 100 Mbps GPON + HBO MAX JUL23', '100086648', 25.00, 1, $fechaActual, $fechaActual],
            ['1 PLAY - Elite 200 Mbps GPON JUL23', '100086649', 35.00, 1, $fechaActual, $fechaActual],
            ['1 PLAY - Master 500 Mbps GPON + HBO MAX JUL23', '100086613', 70.00, 1, $fechaActual, $fechaActual],
            [' 1 PLAY - Premium 1Gbps GPON + HBO MAX JUL23', '100086620', 135.00, 1, $fechaActual, $fechaActual],
            ['1 PLAY - Esencial 50 Mbps HFC JUL23', '100086532', 20.00, 1, $fechaActual, $fechaActual],
            [' 1 PLAY - Super Conectado 75 Mbps HFC JUL23', '100086533', 22.00, 1, $fechaActual, $fechaActual],
            ['1 PLAY - Hiperconectado 100 Mbps + HBO MAX HFC JUL23', '100086534', 25.00, 1, $fechaActual, $fechaActual],
            ['1 PLAY - Elite 200 Mbps HFC JUL23', '100086535', 35.00, 1, $fechaActual, $fechaActual],
            [' 1 PLAY - Master 500 Mbps + HBO MAX HFC JUL23', '100086529', 70.00, 1, $fechaActual, $fechaActual],
            ['1PLAY -  TODO CLARO Esencial 50 Mbps Promo 100 Mbps GPON ', '100087218', 15.00, 1, $fechaActual, $fechaActual],
            ['1PLAY -  TODO CLARO Super Conectado 75 Mbps Promo 150 Mbps GPON', '100087233', 16.00, 1, $fechaActual, $fechaActual],
            ['1PLAY -   TODO CLARO Hiperconectado 100 Mbps + HBO MAX Promo 250 Mbps GPON', '100087234', 17.00, 1, $fechaActual, $fechaActual],
            ['1PLAY -  TODO CLARO Elite 200 Mbps Promo 400 Mbps GPON', '100087250', 25.00, 1, $fechaActual, $fechaActual],
            ['1PLAY -  TODO CLARO Master 500 Mbps + HBO MAX GPON', '100087252', 50.00, 1, $fechaActual, $fechaActual],
            ['1PLAY - TODO CLARO Premium 1 Gbps + HBO MAX GPON', '100087268', 100.00, 1, $fechaActual, $fechaActual],
            ['1PLAY -  TODO CLARO Esencial 50 Mbps Promo 100 Mbps HFC ', '100087224', 15.00, 1, $fechaActual, $fechaActual],
            ['1PLAY -  TODO CLARO Super Conectado 75 Mbps Promo 150 Mbps HFC ', '100087226', 16.00, 1, $fechaActual, $fechaActual],
            ['1PLAY -  TODO CLARO Hiperconectado 100 Mbps Promo 250 Mbps + HBO MAX HFC ', '100087265', 17.00, 1, $fechaActual, $fechaActual],
            ['1PLAY -  TODO CLARO Elite 200 Mbps Promo 400 Mbps HFC ', '100087269', 25.00, 1, $fechaActual, $fechaActual],
            ['1PLAY -  TODO CLARO Master 500 Mbps  + HBO MAX HFC ', '100087272', 50.00, 1, $fechaActual, $fechaActual],
            ['1PLAY -  CAMBIATE Esencial 50 Mbps Promo 100 Mbps GPON ', '100086541', 15.00, 1, $fechaActual, $fechaActual],
            ['1PLAY -  CAMBIATE Super Conectado 75 Mbps Promo 150 Mbps GPON', '100086543', 16.00, 1, $fechaActual, $fechaActual],
            ['1PLAY -   CAMBIATE Hiperconectado 100 Mbps + HBO MAX Promo 250 Mbps GPON', '100086563', 17.00, 1, $fechaActual, $fechaActual],
            ['1PLAY -  CAMBIATE Elite 200 Mbps Promo 400 Mbps GPON', '100086565', 25.00, 1, $fechaActual, $fechaActual],
            ['1PLAY -  CAMBIATE Master 500 Mbps + HBO MAX GPON', '100086566', 50.00, 1, $fechaActual, $fechaActual],
            ['1PLAY - CAMBIATE Premium 1 Gbps + HBO MAX GPON', '100086562', 100.00, 1, $fechaActual, $fechaActual],
            ['1PLAY -  CAMBIATE Esencial 50 Mbps Promo 100 Mbps HFC ', '100086593', 15.00, 1, $fechaActual, $fechaActual],
            ['1PLAY -  CAMBIATE Super Conectado 75 Mbps Promo 150 Mbps HFC ', '100086595', 16.00, 1, $fechaActual, $fechaActual],
            ['1PLAY -  CAMBIATE Hiperconectado 100 Mbps Promo 250 Mbps + HBO MAX HFC ', '100086597', 17.00, 1, $fechaActual, $fechaActual],
            ['1PLAY -  CAMBIATE Elite 200 Mbps Promo 400 Mbps HFC ', '100086642', 25.00, 1, $fechaActual, $fechaActual],
            ['1PLAY -  CAMBIATE Master 500 Mbps  + HBO MAX HFC ', '100086644', 50.00, 1, $fechaActual, $fechaActual],

        ];
        foreach ($datos as $fila) {
            DB::insert('INSERT INTO `ventas_productos_ventas` (`nombre`,`bundle_id`, `precio`,  `plan_id`, `created_at`,  `updated_at`) VALUES(?,?,?,?,?,?)', $fila);
        }
    }
}
