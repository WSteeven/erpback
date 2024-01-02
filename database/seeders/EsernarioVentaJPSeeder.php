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
                'apoyo_das_fijos' => 80,
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
                'apoyo_das_fijos' => 90,
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
                'apoyo_das_fijos' => 100,
                'vendedores' => 12,
                'productividad_minima' => 11,
                'vendedores_acumulados' => 33,
                'total_ventas_adicionales' => 100,
                'arpu_prom' => 21.92,
                'altas' => 12,
                'bajas' => 10,
                'neta' => 2,
                'stock' => 100,
                'stock_que_factura' => 100,
            ],
        ];

        // Insertar los datos
        foreach ($datos as $dato) {
            EscenarioVentaJP::create($dato);
        }
    }
}
