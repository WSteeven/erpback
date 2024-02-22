<?php

namespace Database\Seeders;

use App\Models\Medico\Respuesta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RespuestaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Respuesta::insert([
            //respuestas1_4a
            [
                'respuesta' => 'Muy clara', //1
                'valor' => 1,
            ],
            [
                'respuesta' => 'Clara', //2
                'valor' => 2,
            ],
            [
                'respuesta' => 'Poco clara', //3
                'valor' => 3,
            ],
            [
                'respuesta' => 'Nada clara', //4
                'valor' => 4,
            ],
            //respuestas1_4b
            [
                'respuesta' => 'Siempre o casi siempre', //5
                'valor' => 1,
            ],
            [
                'respuesta' => 'A menudo', //6
                'valor' => 2,
            ],
            [
                'respuesta' => 'A veces', //7
                'valor' => 3,
            ],
            [
                'respuesta' => 'Nunca o casi nunca', //8
                'valor' => 4,
            ],
            //respuestas1_5
            [
                'respuesta' => 'No tengo, no hay otras personas', //9
                'valor' => 5,
            ],
            //respuestas1_5a
            [
                'respuesta' => 'No trabajo turnos rotativos', //10
                'valor' => 5,
            ],
            //respuestas1_6
            [
                'respuesta' => 'Puede decidir', //11
                'valor' => 1,
            ],
            [
                'respuesta' => 'Se me consulta', //12
                'valor' => 2,
            ],
            [
                'respuesta' => 'Solo recibo informacion', //13
                'valor' => 3,
            ],
            [
                'respuesta' => 'Ninguna participacion', //14
                'valor' => 4,
            ],            [
                'respuesta' => 'No interviene', //15
                'valor' => 1,
            ],            [
                'respuesta' => 'Insuficiuente', //16
                'valor' => 2,
            ],            [
                'respuesta' => 'Adecuada', //17
                'valor' => 3,
            ],            [
                'respuesta' => 'Excesiva', //18
                'valor' => 4,
            ],
        ]);
    }
}
