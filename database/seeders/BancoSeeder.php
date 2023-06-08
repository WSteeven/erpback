<?php

namespace Database\Seeders;

use App\Models\RecursosHumanos\Banco ;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BancoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Banco::insert([
            ['nombre' => 'Produbanco'],
            ['nombre' => 'Pichincha'],
            ['nombre' => 'Guayaquil'],
            ['nombre' => 'Pacifico'],
            ['nombre' => 'Internacional'],
            ['nombre' => 'Bolivariano'],
            ['nombre' => 'Banco del Austro'],
            ['nombre' => 'Banco de Loja'],
            ['nombre' => 'Banco de Machala'],
            ['nombre' => 'Banco de Guayaquil'],
        ]);
    }
}
