<?php

namespace Database\Seeders;

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
                'codigo' => '1',
                'pregunta' => 'PRINCIPAL DROGA QUE CONSUME',
            ],
            [
                'codigo' => '2',
                'pregunta' => 'EN CASO DE SELECCIONAR "OTRA", ESPECIFIQUE CUAL',
            ],
            [
                'codigo' => '3',
                'pregunta' => 'OTRAS DROGA  QUE CONSUME',
            ],
            [
                'codigo' => '4',
                'pregunta' => 'FRECUENCIA DE CONSUMO',
            ],
            [
                'codigo' => '5',
                'pregunta' => 'EMPLEADO RECONOCE TENER UN PROBLEMA DE CONSUMO ',
            ],

        ]);
    }
}
