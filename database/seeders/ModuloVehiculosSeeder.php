<?php

namespace Database\Seeders;

use Database\Seeders\RecursosHumanos\Vehiculos\PermisosModuloVehiculosSeeder;
use Database\Seeders\RecursosHumanos\Vehiculos\ServicioSeeder;
use Database\Seeders\RecursosHumanos\Vehiculos\TipoVehiculoSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuloVehiculosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermisosModuloVehiculosSeeder::class,
            ServicioSeeder::class,
            TipoVehiculoSeeder::class,
        ]);
    }
}
