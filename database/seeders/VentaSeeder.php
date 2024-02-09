<?php

namespace Database\Seeders;

use App\Models\Ventas\ClienteClaro;
use App\Models\Ventas\Comision;
use App\Models\Ventas\ProductoVenta;
use App\Models\Ventas\Vendedor;
use App\Models\Ventas\Venta;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class VentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $fechaInicial = Carbon::createFromFormat('Y-m-d', '2024-01-01');

        for ($i = 0; $i < 200; $i++) {
            $venta = new Venta();

            // $venta->orden_id = implode(',', $faker->randomElements([0, 1, 2, 3, 4, 5, 6, 7, 8, 9], 14, true));
            $venta->orden_id = str_pad(random_int(0, 99999999999999), 14, '0', STR_PAD_LEFT);
            $venta->orden_interna = null;
            $venta->supervisor_id = Vendedor::inRandomOrder()->where('tipo_vendedor', Vendedor::SUPERVISOR_VENTAS)->first()->id;
            $venta->vendedor_id = Vendedor::inRandomOrder()->where('tipo_vendedor', Vendedor::VENDEDOR)->first()->empleado_id;
            $venta->producto_id = ProductoVenta::inRandomOrder()->first()->id;
            // $venta->estado_activacion = $faker->randomElement([Venta::ACTIVADO, Venta::APROBADO]);
            $venta->estado_activacion = Venta::ACTIVADO;
            $venta->fecha_activacion = $venta->estado_activacion == Venta::ACTIVADO ? $faker->dateTimeBetween($fechaInicial, 'now')->format('Y-m-d') : null;
            $venta->forma_pago = $faker->randomElement(['EFECTIVO', 'TC', 'D. BANCARIO']);
            [$comision_valor, $comision] = Comision::calcularComisionVenta($venta->vendedor_id, $venta->producto_id, $venta->forma_pago);
            $venta->comision_id = $comision->id;
            $venta->chargeback = 0;
            $venta->comision_vendedor = $venta->estado_activacion == Venta::ACTIVADO ? $comision_valor : 0;
            $venta->cliente_id = ClienteClaro::inRandomOrder()->first()->id;
            $venta->activo = true;
            $venta->observacion = null;
            $venta->primer_mes = $faker->randomElement([true, false]);
            $venta->fecha_pago_primer_mes = $venta->primer_mes && !is_null($venta->fecha_activacion) ? $faker->dateTimeBetween(Carbon::createFromFormat('Y-m-d', $venta->fecha_activacion), 'now')->format('Y-m-d') : null;
            $venta->created_at = $faker->dateTimeBetween($fechaInicial, 'now');
            $venta->comisiona = Venta::obtenerVentaComisiona($venta->vendedor_id);
            $venta->comision_pagada = false;
            $venta->save();
        }
    }
}
