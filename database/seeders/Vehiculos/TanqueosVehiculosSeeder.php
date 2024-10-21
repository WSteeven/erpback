<?php

namespace Database\Seeders\RecursosHumanos\Vehiculos;

use App\Models\Empleado;
use App\Models\Vehiculos\Combustible;
use App\Models\Vehiculos\Tanqueo;
use App\Models\Vehiculos\Vehiculo;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TanqueosVehiculosSeeder extends Seeder
{
    // php artisan db:seed --class="Database\Seeders\Vehiculos\TanqueosVehiculosSeeder"

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $fechaInicial = Carbon::parse('2024-06-01');
        $fechaFinal = Carbon::now();

        for ($i = 0; $i < 200; $i++) {
            $tanqueo = new Tanqueo();
            $tanqueo->vehiculo_id = Vehiculo::inRandomOrder()->first()->id;
            $tanqueo->solicitante_id = Empleado::inRandomOrder()->where('estado', true)->first()->id;
            $tanqueo->fecha_hora = $faker->dateTimeBetween($fechaInicial, $fechaFinal);

            //ultimo tanqueo
            $ultimoTanqueo = Tanqueo::where('vehiculo_id', $tanqueo->vehiculo_id)->orderBy('id', 'desc')->first();
            if ($ultimoTanqueo) {
                $tanqueo->km_tanqueo = $faker->numberBetween($ultimoTanqueo->km_tanqueo, $ultimoTanqueo->km_tanqueo + 10000);
            } else {
                $tanqueo->km_tanqueo = $faker->numberBetween(100000, +10000);
            }
            $tanqueo->imagen_comprobante = null;
            $tanqueo->imagen_tablero = null;
            $tanqueo->monto = $faker->randomFloat(2, 5, 20);
            $tanqueo->combustible_id = Vehiculo::find($tanqueo->vehiculo_id)->combustible_id;
            // $tanqueo->combustible_id = Combustible::inRandomOrder()->first()->id;
            $tanqueo->save();
        }
    }
}
