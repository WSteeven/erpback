<?php

namespace Database\Seeders\Conecel;

use App\Models\Conecel\GestionTareas\TipoActividad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatosPruebaGestionTareasConecelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\Conecel\DatosPruebaGestionTareasConecelSeeder"
     * @return void
     */
    public function run()
    {

        TipoActividad::create(['nombre' => 'INSTALACION']);


    }
}
