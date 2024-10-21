<?php

namespace Database\Seeders;

use Database\Seeders\RecursosHumanos\Intranet\CategoriaSeeder;
use Database\Seeders\RecursosHumanos\Intranet\EtiquetasSeeder;
use Database\Seeders\RecursosHumanos\Intranet\PermisosModuloIntranetSeeder;
use Database\Seeders\RecursosHumanos\Intranet\TipoEventoSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuloIntranetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=ModuloIntranetSeeder
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CategoriaSeeder::class,
            EtiquetasSeeder::class,
            TipoEventoSeeder::class,
            PermisosModuloIntranetSeeder::class,
        ]);
    }
}
