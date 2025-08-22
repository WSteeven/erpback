<?php

namespace Database\Seeders\VentasClaro;

use App\Models\Ventas\ClienteClaro;
use App\Models\Ventas\Comision;
use App\Models\Ventas\ProductoVenta;
use App\Models\Ventas\Vendedor;
use App\Models\Ventas\Venta;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class VentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();
        $fechaInicial = Carbon::createFromFormat('Y-m-d', '2024-01-01');
        $fechaFinal = Carbon::createFromFormat('Y-m-d', '2024-01-31');

        for ($i = 0; $i < 150; $i++) {
            $venta = new Venta();

            // Fechas en orden correcto
            $createdAt = $faker->dateTimeBetween($fechaInicial, $fechaFinal);
            $venta->created_at = $createdAt;
            $venta->updated_at = $createdAt;
            $venta->fecha_ingreso = $createdAt->format('Y-m-d');

            // Datos base
            $venta->orden_id = str_pad(random_int(0, 99999999999999), 14, '0', STR_PAD_LEFT);
            $venta->supervisor_id = Vendedor::inRandomOrder()->where('tipo_vendedor', Vendedor::SUPERVISOR_VENTAS)->first()->id;
            $venta->vendedor_id = Vendedor::inRandomOrder()->where('tipo_vendedor', Vendedor::VENDEDOR)->first()->empleado_id;
            $venta->producto_id = ProductoVenta::inRandomOrder()->first()->id;
            $venta->cliente_id = ClienteClaro::inRandomOrder()->first()->id;

            // Activación
            $venta->estado_activacion = Venta::ACTIVADO;
            $venta->fecha_activacion = $venta->estado_activacion == Venta::ACTIVADO
                ? $faker->dateTimeBetween($fechaInicial, $createdAt)->format('Y-m-d')
                : null;

            // Forma de pago y cuenta
            $venta->forma_pago = $faker->randomElement(['EFECTIVO', 'TC', 'D. BANCARIO']);
            $venta->banco = $faker->company;
            $venta->numero_tarjeta = $faker->creditCardNumber;
            $venta->tipo_cuenta = $faker->randomElement(['AHORROS', 'CORRIENTE']);

            // Comisiones
            [$comision_valor, $comision] = Comision::calcularComisionVenta($venta->vendedor_id, $venta->producto_id, $venta->forma_pago);
            $venta->comision_id = $comision->id;
            $venta->chargeback = 0;
            $venta->comision_vendedor = $venta->estado_activacion == Venta::ACTIVADO ? $comision_valor : 0;
            $venta->comisiona = Venta::obtenerVentaComisiona($venta->vendedor_id);
            $venta->comision_pagada = false;

            // Estado y detalles adicionales
            $venta->estado_id = $faker->numberBetween(1, 7);
            $venta->activo = true;
            $venta->observacion = $faker->optional()->sentence();
            $venta->adicionales = $faker->optional()->text(100);

            // Primer mes
            $venta->primer_mes = $faker->boolean();
            $venta->fecha_pago_primer_mes = $venta->primer_mes && !is_null($venta->fecha_activacion)
                ? $faker->dateTimeBetween(Carbon::createFromFormat('Y-m-d', $venta->fecha_activacion), $fechaFinal)->format('Y-m-d')
                : null;

            $venta->save();
        }
    }
}
