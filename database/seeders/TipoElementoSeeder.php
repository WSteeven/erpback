<?php

namespace Database\Seeders;

use App\Models\TipoElemento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoElementoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoElemento::insert([
            ['nombre' => 'POSTE'],
            ['nombre' => 'CAJA'],
            ['nombre' => 'AMERICANO'],
            ['nombre' => 'RADIO BASE'],
            ['nombre' => 'NODO'],
        ]);
    }
}
