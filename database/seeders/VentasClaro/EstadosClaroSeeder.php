<?php

namespace Database\Seeders\VentasClaro;

use App\Models\Ventas\EstadoClaro;
use Illuminate\Database\Seeder;

class EstadosClaroSeeder extends Seeder
{
    /**
     * php artisan db:seed --class=Database\Seeders\VentasClaro\EstadosClaroSeeder
     * @return void
     */
    public function run()
    {
        // Para clientes de claro
        EstadoClaro::firstOrCreate(['nombre' => 'ACTIVO', 'abreviatura' => 'A', 'tipo' => 'CLIENTE']);
        EstadoClaro::firstOrCreate(['nombre' => 'SALVABLE', 'abreviatura' => 'X', 'tipo' => 'CLIENTE']);
        EstadoClaro::firstOrCreate(['nombre' => 'PROSPECTO', 'abreviatura' => 'W', 'tipo' => 'CLIENTE']);
        EstadoClaro::firstOrCreate(['nombre' => 'DESCARTADO', 'abreviatura' => 'Z', 'tipo' => 'CLIENTE']);

        // Para ventas de claro
        EstadoClaro::firstOrCreate(['nombre' => 'ACTIVO', 'abreviatura' => 'A', 'tipo' => 'VENTA']);
        EstadoClaro::firstOrCreate(['nombre' => 'PENDIENTE INSTALAR', 'abreviatura' => 'P', 'tipo' => 'VENTA']);
        EstadoClaro::firstOrCreate(['nombre' => 'PENDIENTE BIOMETRICO', 'abreviatura' => 'B', 'tipo' => 'VENTA']);
    }
}
