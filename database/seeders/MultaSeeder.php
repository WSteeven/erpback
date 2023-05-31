<?php

namespace Database\Seeders;

use App\Models\RecursosHumanos\NominaPrestamos\Multas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MultaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Multas::insert([
            ['nombre' => 'De acuerdo a reglamento Interno'],
            ['nombre' => 'Citacion de Transito']
        ]);
    }
}
