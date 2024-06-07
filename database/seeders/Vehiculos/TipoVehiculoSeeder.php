<?php

namespace Database\Seeders\Vehiculos;

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
        TipoVehiculo::create(['nombre'=>'SEDAN']);
        TipoVehiculo::create(['nombre'=>'HATHBACK']);
        TipoVehiculo::create(['nombre'=>'SUV']);
        TipoVehiculo::create(['nombre'=>'CAMIONETA']);
        TipoVehiculo::create(['nombre'=>'PICK-UP']);
        TipoVehiculo::create(['nombre'=>'MOTOCICLETA']);
        TipoVehiculo::create(['nombre'=>'CAMION LIVIANO']);
        TipoVehiculo::create(['nombre'=>'CAMION MEDIANO']);
        TipoVehiculo::create(['nombre'=>'CAMION PESADO']);
        TipoVehiculo::create(['nombre'=>'CAMION VOLQUETE']);
        TipoVehiculo::create(['nombre'=>'CAMION PLATAFORMAÑ']);
        TipoVehiculo::create(['nombre'=>'CAMION GRUA']);
    }
}
