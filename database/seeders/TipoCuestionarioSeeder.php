<?php

namespace Database\Seeders;

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
                'titulo' => 'CUESTIONARIO PSICOSOCIAL',
            ],
            [
                'titulo' => 'CUESTIONARIO DIAGNOSTICO CONSUMO DE DROGAS',
            ]
        ]);
    }
}
