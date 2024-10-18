<?php

namespace Database\Seeders\RecursosHumanos\Vehiculos;

use App\Models\Vehiculos\TipoVehiculo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoVehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        TipoVehiculo::firstOrCreate(['nombre' => 'SEDAN']);
        TipoVehiculo::firstOrCreate(['nombre' => 'HATHBACK']);
        TipoVehiculo::firstOrCreate(['nombre' => 'SUV']);
        TipoVehiculo::firstOrCreate(['nombre' => 'CAMIONETA']);
        TipoVehiculo::firstOrCreate(['nombre' => 'PICK-UP']);
        TipoVehiculo::firstOrCreate(['nombre' => 'MOTOCICLETA']);
        TipoVehiculo::firstOrCreate(['nombre' => 'CAMION LIVIANO']);
        TipoVehiculo::firstOrCreate(['nombre' => 'CAMION MEDIANO']);
        TipoVehiculo::firstOrCreate(['nombre' => 'CAMION PESADO']);
        TipoVehiculo::firstOrCreate(['nombre' => 'CAMION VOLQUETE']);
        TipoVehiculo::firstOrCreate(['nombre' => 'CAMION PLATAFORMAÑ']);
        TipoVehiculo::firstOrCreate(['nombre' => 'CAMION GRUA']);
    }
}
