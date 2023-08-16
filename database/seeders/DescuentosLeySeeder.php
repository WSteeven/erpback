<?php

namespace Database\Seeders;

use App\Models\RecursosHumanos\NominaPrestamos\DescuentosLey;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DescuentosLeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DescuentosLey::insert([
            ['nombre' => 'Aporte IESS'],
            ['nombre' => 'SUPA'],
            ['nombre' => 'Extension de Cobertura de Salud'],
            ['nombre' => 'Prestamo Hipotecario'],
            ['nombre' => 'Prestamo Quirorafario']
        ]);
    }
}
