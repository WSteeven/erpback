<?php

namespace Database\Seeders\RecursosHumanos\ActivosFijos;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuloActivosFijosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\ActivosFijos\ModuloActivosFijosSeeder"
     * @return void
     */
    public function run()
    {
        /************************
         * Modulo Activos Fijos
         ************************/
        $this->call([ // No cambiar el orden
            // PermisosActivosFijosSeeder::class,
            CategoriaMotivoConsumoActivoFijoSeeder::class,
            MotivoConsumoActivoFijoSeeder::class,
        ]);
    }
}
