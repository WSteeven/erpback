<?php

namespace Database\Seeders;

use Database\Seeders\RecursosHumanos\SeleccionContratacion\PermisosSeleccionContratacionSeeder;
use Database\Seeders\RecursosHumanos\SeleccionContratacion\TipoPuestoTrabajoSeeder;
use Illuminate\Database\Seeder;

class ModuloSeleccionContratacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*****************
         * Módulo Seleccion y contratacion de personal
         *****************/
        // php artisan db:seed --class=ModuloSeleccionContratacionSeeder
        $this->call([
            PermisosSeleccionContratacionSeeder::class,
            TipoPuestoTrabajoSeeder::class
        ]);
    }
}
