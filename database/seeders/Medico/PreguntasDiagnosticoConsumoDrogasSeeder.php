<?php

namespace Database\Seeders\Medico;

use App\Models\Medico\Pregunta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PreguntasDiagnosticoConsumoDrogasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pregunta::insert([

            [
                'codigo' => '1', // 90
                'pregunta' => 'PRINCIPAL DROGA QUE CONSUME',
            ],
            [
                'codigo' => '2', // 91
                'pregunta' => 'EN CASO DE SELECCIONAR "OTRA", ESPECIFIQUE CUAL',
            ],
            [
                'codigo' => '3', // 92
                'pregunta' => 'OTRAS DROGAS QUE CONSUME',
            ],
            [
                'codigo' => '4', // 93
                'pregunta' => 'FRECUENCIA DE CONSUMO',
            ],
            [
                'codigo' => '5', // 94
                'pregunta' => 'EMPLEADO RECONOCE TENER UN PROBLEMA DE CONSUMO ',
            ],
            [
                'codigo' => '6', // 95
                'pregunta' => 'FACTORES PSICO-SOCIALES RELACIONADOS AL CONSUMO ',
            ],
        ]);
    }
    // 
}
