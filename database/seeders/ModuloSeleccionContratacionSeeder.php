<?php

namespace Database\Seeders;

use Database\Seeders\RecursosHumanos\SeleccionContratacion\PermisosSeleccionContratacionSeeder;
use Database\Seeders\RecursosHumanos\SeleccionContratacion\TipoPuestoTrabajoSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
         * Modulo Seleccion y contratacion  de personal
         *****************/
        $this->call([
           // PermisosSeleccionContratacionSeeder::class,
            TipoPuestoTrabajoSeeder::class
        ]);
    }
}
