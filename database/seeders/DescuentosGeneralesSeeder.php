<?php

namespace Database\Seeders;

use App\Models\RecursosHumanos\NominaPrestamos\DescuentosGenerales;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DescuentosGeneralesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DescuentosGenerales::insert([
            ['nombre' => 'Perdida o Mal Uso de Bienes'],
            ['nombre' => 'Atraso'],
            ['nombre' => 'Falta Injustificada'],
            ['nombre' => 'Prestamo Empresarial'],
            ['nombre' => 'Fondo Rotativo'],
            ['nombre' => 'Subsidio al IESS']
        ]);
    }
}
