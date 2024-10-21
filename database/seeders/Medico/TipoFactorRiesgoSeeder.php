<?php

namespace Database\Seeders\RecursosHumanos\Medico;

use App\Models\Medico\TipoFactorRiesgo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoFactorRiesgoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\Medico\TipoFactorRiesgoSeeder"
     *
     * @return void
     */
    public function run()
    {
        TipoFactorRiesgo::insert([
            ['nombre'=>'Fisico'],
            ['nombre'=>'Mecánico'],
            ['nombre'=>'Quimico'],
            ['nombre'=>'Biologico'],
            ['nombre'=>'Ergonomico'],
            ['nombre'=>'Psicosocial'],
        ]);
    }
}
