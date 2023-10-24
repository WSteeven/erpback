<?php

namespace Database\Seeders;

use App\Models\Ventas\Modalidad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModalidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Modalidad::insert([
            ['nombre'=> 'TIEMPO COMPLETO','umbral_minimo'=>14],
            ['nombre'=> 'MEDIO TIEMPO','umbral_minimo'=>7],
            ['nombre'=> 'FREELANCE','umbral_minimo'=>1],
        ]);
    }
}
