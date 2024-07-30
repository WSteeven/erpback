<?php

namespace Database\Seeders;

use Database\Seeders\Intranet\CategoriaSeeder;
use Database\Seeders\Intranet\EtiquetasSeeder;
use Database\Seeders\Intranet\TipoEventoSeeder;
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
        ]);
    }
}
