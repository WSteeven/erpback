<?php

namespace Database\Seeders\ActivosFijos;

use App\Models\ActivosFijos\CategoriaMotivoConsumoActivoFijo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaMotivoConsumoActivoFijoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CategoriaMotivoConsumoActivoFijo::insert([
            ['nombre' => 'DEFENSA PROPIA'], // 1
            ['nombre' => 'DEFENSA DE TERCEROS'], // 2
            ['nombre' => 'INTERVENCIÓN EN DELITOS'], // 3
            ['nombre' => 'SITUACIONES DE EMERGENCIA'], // 4
            ['nombre' => 'ALERTAS Y ADVERTENCIAS'], // 5
            ['nombre' => 'CONTROL DE ANIMALES PELIGROSOS'], // 6
            ['nombre' => 'PRÁCTICAS Y ENTRENAMIENTO'], // 7
            ['nombre' => 'ACCIDENTES'], // 8
            ['nombre' => 'CUMPLIMIENTO DE ÓRDENES'], // 9
            ['nombre' => 'PROTECCIÓN DE BIENES Y PROPIEDADES'], // 10
            ['nombre' => 'PROCEDIMIENTOS DE SEGURIDAD'], // 11
        ]);
    }
}
