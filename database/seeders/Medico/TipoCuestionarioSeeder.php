<?php

namespace Database\Seeders\RecursosHumanos\Medico;

use App\Models\Medico\TipoCuestionario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoCuestionarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoCuestionario::insert([
            [
                'titulo' => 'CUESTIONARIO DE EVALUACIÓN DE RIESGOS PSICOSOCIALES',
            ],
            [
                'titulo' => 'DIAGNÓSTICO INICIAL PROGRAMA INTEGRAL DE REDUCCIÓN Y PREVENCIÓN DEL USO Y CONSUMO DE DROGAS',
            ]
        ]);
    }
}
