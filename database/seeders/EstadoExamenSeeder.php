<?php

namespace Database\Seeders;

use App\Models\EstadoExamen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadoExamenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EstadoExamen::insert([
            [
                'nombre' => 'Solicitado',
            ],
            [
                'nombre' => 'Aprobado por compras',
            ],
            [
                'nombre' => 'Diagnóstico realizado',
            ],
            [
                'nombre' => 'Apertura de ficha',
            ],
        ]);
    }
}
