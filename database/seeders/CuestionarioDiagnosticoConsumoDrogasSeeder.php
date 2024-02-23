<?php

namespace Database\Seeders;

use App\Models\Medico\Cuestionario;
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
                'pregunta_id' => 89,
                'respuesta_id' => 67,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 89,
                'respuesta_id' => 68,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 89,
                'respuesta_id' => 69,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 89,
                'respuesta_id' => 70,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 89,
                'respuesta_id' => 71,
                'tipo_cuestionario_id' => 2,
            ],
            //Pregunta 2
            [
                'pregunta_id' => 90,
                'respuesta_id' => null,
                'tipo_cuestionario_id' => 2,
            ],
            //Pregunta 3
            [
                'pregunta_id' => 91,
                'respuesta_id' => null,
                'tipo_cuestionario_id' => 2,
            ],
            //Pregunta 4
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
            [
                'pregunta_id' => 92,
                'respuesta_id' => 74,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 92,
                'respuesta_id' => 75,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 92,
                'respuesta_id' => 76,
                'tipo_cuestionario_id' => 2,
            ],
            [
                'pregunta_id' => 92,
                'respuesta_id' => 77,
                'tipo_cuestionario_id' => 2,
            ],
            //Pregunta 5
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
            //Pregunta 6
            [
                'pregunta_id' => 94,
                'respuesta_id' => null,
                'tipo_cuestionario_id' => 2,
            ],
        ]);
    }
}
