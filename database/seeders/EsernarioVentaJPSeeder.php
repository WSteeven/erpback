<?php

namespace Database\Seeders;

use App\Models\Ventas\EscenarioVentaJP;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EsernarioVentaJPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear datos de ejemplo
        $datos = [
            [
                'mes' => 1,
                'vendedores' => 10,
                'productividad_minima' => 9,
                'vendedores_acumulados' => 10,
                'total_ventas_adicionales' => 80,
                'arpu_prom' => 19.92,
                'altas' => 80,
                'bajas' => 80,
                'neta' => 0,
                'stock' => 80,
                'stock_que_factura' => 80,
            ],
            [
                'mes' => 2,
                'vendedores' => 11,
                'productividad_minima' => 10,
                'vendedores_acumulados' => 21,
                'total_ventas_adicionales' => 90,
                'arpu_prom' => 20.92,
                'altas' => 11,
                'bajas' => 9,
                'neta' => 2,
                'stock' => 90,
                'stock_que_factura' => 90,
            ],
            [
                'mes' => 3,

                'vendedores' => 12,
                'productividad_minima' => 11,
                'vendedores_acumulados' => 33,
                'total_ventas_adicionales' => 100,
                'arpu_prom' => 19.92,
                'altas' => 12,
                'bajas' => 10,
                'neta' => 2,
                'stock' => 100,
                'stock_que_factura' => 100,
            ], [
                'mes' => 4,
                'vendedores' => 10,
                'productividad_minima' => 9,
                'vendedores_acumulados' => 35,
                'total_ventas_adicionales' => 300,
                'arpu_prom' => 19.92,
                'altas' => 300,
                'bajas' => 0,
                'neta' => 300,
                'stock' => 710,
                'stock_que_factura' => 0,
            ], [
                'mes' => 5,
                'vendedores' => 5,
                'productividad_minima' => 10,
                'vendedores_acumulados' => 40,
                'total_ventas_adicionales' => 435,
                'arpu_prom' => 19.92,
                'altas' => 435,
                'bajas' => 0,
                'neta' => 435,
                'stock' => 1145,
                'stock_que_factura' => 0,
            ], [
                'mes' => 6,
                'vendedores' => 10,
                'productividad_minima' => 10,
                'vendedores_acumulados' => 50,
                'total_ventas_adicionales' => 495,
                'arpu_prom' => 19.92,
                'altas' => 495,
                'bajas' => 0,
                'neta' => 495,
                'stock' => 1640,
                'stock_que_factura' => 0,
            ], [
                'mes' => 7,
                'vendedores' => 15,
                'productividad_minima' => 11,
                'vendedores_acumulados' => 65,
                'total_ventas_adicionales' => 525,
                'arpu_prom' => 19.92,
                'altas' => 525,
                'bajas' => 10,
                'neta' => 525,
                'stock' => 1640,
                'stock_que_factura' => 0,
            ], [
                'mes' => 8,
                'vendedores' => 5,
                'productividad_minima' => 11,
                'vendedores_acumulados' => 70,
                'total_ventas_adicionales' => 550,
                'arpu_prom' => 19.92,
                'altas' => 550,
                'bajas' => 0,
                'neta' => 550,
                'stock' => 2715,
                'stock_que_factura' => 0,
            ], [
                'mes' => 9,
                'vendedores' => 10,
                'productividad_minima' => 12,
                'vendedores_acumulados' => 80,
                'total_ventas_adicionales' => 580,
                'arpu_prom' => 19.92,
                'altas' => 580,
                'bajas' => 0,
                'neta' => 605,
                'stock' => 3900,
                'stock_que_factura' => 0,
            ], [
                'mes' => 10,
                'vendedores' => 10,
                'productividad_minima' => 12,
                'vendedores_acumulados' => 90,
                'total_ventas_adicionales' => 605,
                'arpu_prom' => 19.92,
                'altas' => 605,
                'bajas' => 0,
                'neta' => 605,
                'stock' => 3900,
                'stock_que_factura' => 0,
            ], [
                'mes' => 11,
                'vendedores' => 5,
                'productividad_minima' => 13,
                'vendedores_acumulados' => 95,
                'total_ventas_adicionales' => 635,
                'arpu_prom' => 19.92,
                'altas' => 635,
                'bajas' => 0,
                'neta' => 635,
                'stock' => 4535,
                'stock_que_factura' => 0,
            ], [
                'mes' => 12,
                'vendedores' => 5,
                'productividad_minima' => 14,
                'vendedores_acumulados' => 100,
                'total_ventas_adicionales' => 670,
                'arpu_prom' => 19.92,
                'altas' => 670,
                'bajas' => 0,
                'neta' => 670,
                'stock' => 5205,
                'stock_que_factura' => 0,
            ], [
                'mes' => 13,
                'vendedores' => 5,
                'productividad_minima' => 16,
                'vendedores_acumulados' => 105,
                'total_ventas_adicionales' => 720,
                'arpu_prom' => 19.92,
                'altas' => 760,
                'bajas' => 0,
                'neta' => 760,
                'stock' => 6685,
                'stock_que_factura' => 0,
            ], [
                'mes' => 14,
                'vendedores' => 5,
                'productividad_minima' => 16,
                'vendedores_acumulados' => 110,
                'total_ventas_adicionales' => 760,
                'arpu_prom' => 19.92,
                'altas' => 760,
                'bajas' => 0,
                'neta' => 760,
                'stock' => 6685,
                'stock_que_factura' => 0,
            ], [
                'mes' => 15,
                'vendedores' => 5,
                'productividad_minima' => 16,
                'vendedores_acumulados' => 115,
                'total_ventas_adicionales' => 795,
                'arpu_prom' => 19.92,
                'altas' => 795,
                'bajas' => 0,
                'neta' => 760,
                'stock' => 6685,
                'stock_que_factura' => 0,
            ],
         ];

        // Insertar los datos
        foreach ($datos as $dato) {
            EscenarioVentaJP::create($dato);
        }
    }
}
