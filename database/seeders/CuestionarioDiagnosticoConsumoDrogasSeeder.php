<?php

namespace Database\Seeders;

use App\Models\Medico\Cuestionario;
use App\Models\Medico\TipoCuestionario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CuestionarioDiagnosticoConsumoDrogasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Cuestionario::insert([
            // Pregunta 1
            [
                'pregunta_id' => 90,
                'respuesta_id' => 69,
                'tipo_cuestionario_id' => TipoCuestionario::CUESTIONARIO_DIAGNOSTICO_CONSUMO_DE_DROGAS,
            ],
            [
                'pregunta_id' => 90,
                'respuesta_id' => 70,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 90,
                'respuesta_id' => 71,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 90,
                'respuesta_id' => 72,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 90,
                'respuesta_id' => 73,
                'tipo_cuestionario_id' => 2,
            ],
            //Pregunta 2
            [
                'pregunta_id' => 91,
                'respuesta_id' => null,
                'tipo_cuestionario_id' => 2,
            ],
            //Pregunta 3
            [
                'pregunta_id' => 92,
                'respuesta_id' => 69,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 92,
                'respuesta_id' => 70,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 92,
                'respuesta_id' => 71,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 92,
                'respuesta_id' => 72,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 92,
                'respuesta_id' => 73,
                'tipo_cuestionario_id' => 2,
            ],
            //Pregunta 4
            [
                'pregunta_id' => 93,
                'respuesta_id' => 74,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 93,
                'respuesta_id' => 75,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 93,
                'respuesta_id' => 76,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 93,
                'respuesta_id' => 77,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 93,
                'respuesta_id' => 78,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 93,
                'respuesta_id' => 79,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 93,
                'respuesta_id' => 80,
                'tipo_cuestionario_id' => 2,
            ],
            //Pregunta 5
            [
                'pregunta_id' => 94,
                'respuesta_id' => 81,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 94,
                'respuesta_id' => 82,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 94,
                'respuesta_id' => 83,
                'tipo_cuestionario_id' => 2,
            ],
            //Pregunta 6
            [
                'pregunta_id' => 95,
                'respuesta_id' => 84,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 95,
                'respuesta_id' => 85,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 95,
                'respuesta_id' => 86,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 95,
                'respuesta_id' => 87,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 95,
                'respuesta_id' => 88,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 95,
                'respuesta_id' => 89,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 95,
                'respuesta_id' => 90,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 95,
                'respuesta_id' => 91,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 95,
                'respuesta_id' => 92,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 95,
                'respuesta_id' => 93,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 95,
                'respuesta_id' => 94,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 95,
                'respuesta_id' => 95,
                'tipo_cuestionario_id' => 2,
            ],
        ]);
    }
}
