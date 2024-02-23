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
            // Pregunta 1
            [
                'respuesta' => 'Siempre o casi siempre',
                'valor' => 1,//1
            ],
            [
                'respuesta' => 'A menudo',
                'valor' => 2,//2
            ],
            [
                'respuesta' => 'A veces',
                'valor' => 3,//3
            ],
            [
                'respuesta' => 'Nunca o casi nunca',
                'valor' => 4,//4
            ],
            // Pregunta 10h
            [
                'respuesta' => 'No trabajo en turnos rotativos',
                'valor' => 5,//5
            ],
            // Pregunta 11a
            [
                'respuesta' => 'Puedo decidir',
                'valor' => 1,//6
            ],
            [
                'respuesta' => 'Se me consulta',
                'valor' => 2,//7
            ],
            [
                'respuesta' => 'Solo recibo información',
                'valor' => 3,//8
            ],
            [
                'respuesta' => 'Ninguna Participación',
                'valor' => 4,//9
            ],
            // Pregunta 12a
            [
                'respuesta' => 'No interviene',
                'valor' => 1,//10
            ],
            [
                'respuesta' => 'Insuficiente',
                'valor' => 2,//11
            ],
            [
                'respuesta' => 'Adecuada',
                'valor' => 3,//12
            ],
            [
                'respuesta' => 'Excesiva',
                'valor' => 4,//13
            ],
            // Pregunta 13a
            [
                'respuesta' => 'No hay información',
                'valor' => 1,//14
            ],
            [
                'respuesta' => 'Es adecuada',
                'valor' => 3,//15
            ],

            // Pregunta 14a
            [
                'respuesta' => 'Muy clara',
                'valor' => 4,//16
            ],
            [
                'respuesta' => 'Clara',
                'valor' => 3,//17
            ],
            [
                'respuesta' => 'Poco clara',
                'valor' => 2,//18
            ],
            [
                'respuesta' => 'Nada clara',
                'valor' => 1,//19
            ],
            //Pregunta 16a
            [
                'respuesta' => 'No tengo no hay otras personas',
                'valor' => 5,//20
            ],
            // Pregunta 17
            [
                'respuesta' => 'Buenas',
                'valor' => 4,//21
            ],
            [
                'respuesta' => 'Regulares',
                'valor' => 3,//22
            ],
            [
                'respuesta' => 'Malas',
                'valor' => 2,//23
            ],
            [
                'respuesta' => 'No tengo compañeros',
                'valor' => 1,//24
            ],
            // Pregunta 18a
            [
                'respuesta' => 'Raras veces',
                'valor' => 1,//25
            ],
            [
                'respuesta' => 'Con frecuencia',
                'valor' => 2,//26
            ],
            [
                'respuesta' => 'Constantemente',
                'valor' => 3,//27
            ],
            [
                'respuesta' => 'No existen',
                'valor' => 4,//28
            ],

            // Pregunta 19
            [
                'respuesta' => 'Deja que sean los implicados quienes solucionen el tema',
                'valor' => 4,//29
            ],
            [
                'respuesta' => 'Pide a los mandos de los afectados que traten de buscar una solución al problema',
                'valor' => 3,//30
            ],
            [
                'respuesta' => 'Tiene establecido un procedimiento formal de actuación',
                'valor' => 2,//31
            ],
            [
                'respuesta' => 'No lo sé',
                'valor' => 1,//32
            ],
            // Pregunta 22
            [
                'respuesta' => 'Muy alta',//33
                'valor' => 5,
            ],
            [
                'respuesta' => 'Alta',//34
                'valor' => 4,
            ],
            [
                'respuesta' => 'Media',//35
                'valor' => 3,
            ],
            [
                'respuesta' => 'Baja',//36
                'valor' => 2,
            ],
            [
                'respuesta' => 'Muy baja',//37
                'valor' => 1,
            ],

            // Pregunta 26
            [
                'respuesta' => 'Excesiva',//38
                'valor' => 1,
            ],
            [
                'respuesta' => 'Elevada',//39
                'valor' => 2,
            ],
            [
                'respuesta' => 'Adecuada',//40
                'valor' => 3,
            ],
            [
                'respuesta' => 'Escasa',//41
                'valor' => 4,
            ],
            [
                'respuesta' => 'Muy escasa',//42
                'valor' => 5,
            ],
            //pregunta 34a
            [
                'respuesta' => 'No tengo no trato',//42
                'valor' => 5,
            ],
            // Pregunta 37
            [
                'respuesta' => 'No',//43
                'valor' => 1,
            ],
            [
                'respuesta' => 'A veces',//44
                'valor' => 2,
            ],
            [
                'respuesta' => 'Bastante',//45
                'valor' => 3,
            ],
            [
                'respuesta' => 'Mucho',//46
                'valor' => 4,
            ],

            // Pregunta 38
            [
                'respuesta' => 'Mucho',//47
                'valor' => 1,
            ],
            [
                'respuesta' => 'Bastante',//48
                'valor' => 2,
            ],
            [
                'respuesta' => 'Poco',//49
                'valor' => 3,
            ],
            [
                'respuesta' => 'Nada',//50
                'valor' => 4,
            ],

            // Pregunta 39
            [
                'respuesta' => 'No es muy importante',//51
                'valor' => 1,
            ],
            [
                'respuesta' => 'Es importante',//52
                'valor' => 2,
            ],
            [
                'respuesta' => 'Es muy importante',//53
                'valor' => 3,
            ],
            [
                'respuesta' => 'No lo sé',//54
                'valor' => 4, // No hay opción específica
            ],
            // Pregunta 41
            [
                'respuesta' => 'Adecuadamente',//55
                'valor' => 1,
            ],
            [
                'respuesta' => 'Regular',//56
                'valor' => 2,
            ],
            [
                'respuesta' => 'Insuficientemente',//57
                'valor' => 3,
            ],
            [
                'respuesta' => 'No existe posibilidad de desarrollo profesional',//58
                'valor' => 4, // No hay opción específica
            ],
            // Pregunta 42
            [
                'respuesta' => 'Muy adecuada',//59
                'valor' => 4,
            ],
            [
                'respuesta' => 'Suficiente',//60
                'valor' => 3,
            ],
            [
                'respuesta' => 'Insuficiente en algunos casos',//61
                'valor' => 2,
            ],
            [
                'respuesta' => 'Totalmente insuficiente',//62
                'valor' => 1,
            ],
            //Pregunta 44
            [
                'respuesta' => 'Muy satisfecho',//63
                'valor' => 4,
            ],
            [
                'respuesta' => 'Satisfecho',//64
                'valor' => 3,
            ],
            [
                'respuesta' => 'Insatisfecho',//65
                'valor' => 2,
            ],
            [
                'respuesta' => 'Muy insatisfecho',//66
                'valor' => 1,
            ],
        ]);
    }
}
