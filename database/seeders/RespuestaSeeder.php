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
                'valor' => 1,
            ],
            [
                'respuesta' => 'A menudo',
                'valor' => 2,
            ],
            [
                'respuesta' => 'A veces',
                'valor' => 3,
            ],
            [
                'respuesta' => 'Nunca o casi nunca',
                'valor' => 4,
            ],
            // Pregunta 10h
            [
                'respuesta' => 'No trabajo en turnos rotativos',
                'valor' => 5,
            ],
            // Pregunta 11a
            [
                'respuesta' => 'Puedo decidir',
                'valor' => 1,
            ],
            [
                'respuesta' => 'Se me consulta',
                'valor' => 2,
            ],
            [
                'respuesta' => 'Solo recibo información',
                'valor' => 3,
            ],
            [
                'respuesta' => 'Ninguna Participación',
                'valor' => 4,
            ],
            // Pregunta 12a
            [
                'respuesta' => 'No interviene',
                'valor' => 1,
            ],
            [
                'respuesta' => 'Insuficiente',
                'valor' => 2,
            ],
            [
                'respuesta' => 'Adecuada',
                'valor' => 3,
            ],
            [
                'respuesta' => 'Excesiva',
                'valor' => 4,
            ],
            // Pregunta 13a
            [
                'respuesta' => 'No hay información',
                'valor' => 1,
            ],
            [
                'respuesta' => 'Es adecuada',
                'valor' => 3,
            ],

            // Pregunta 14a
            [
                'respuesta' => 'Muy clara',
                'valor' => 4,
            ],
            [
                'respuesta' => 'Clara',
                'valor' => 3,
            ],
            [
                'respuesta' => 'Poco clara',
                'valor' => 2,
            ],
            [
                'respuesta' => 'Nada clara',
                'valor' => 1,
            ],
            // Pregunta 15c
            [
                'respuesta' => 'Siempre o casi siempre',
                'valor' => 4,
            ],
            [
                'respuesta' => 'A menudo',
                'valor' => 3,
            ],
            [
                'respuesta' => 'A veces',
                'valor' => 2,
            ],
            [
                'respuesta' => 'Nunca o casi nunca',
                'valor' => 1,
            ],
            // Pregunta 17
            [
                'respuesta' => 'Buenas',
                'valor' => 4,
            ],
            [
                'respuesta' => 'Regulares',
                'valor' => 3,
            ],
            [
                'respuesta' => 'Malas',
                'valor' => 2,
            ],
            [
                'respuesta' => 'No tengo compañeros',
                'valor' => 1,
            ],
            // Pregunta 18d
            [
                'respuesta' => 'Raras veces',
                'valor' => 4,
            ],
            [
                'respuesta' => 'Con frecuencia',
                'valor' => 3,
            ],
            [
                'respuesta' => 'Constantemente',
                'valor' => 2,
            ],
            [
                'respuesta' => 'No existen',
                'valor' => 1,
            ],

            // Pregunta 19
            [
                'respuesta' => 'Deja que sean los implicados quienes solucionen el tema',
                'valor' => 4,
            ],
            [
                'respuesta' => 'Pide a los mandos de los afectados que traten de buscar una solución al problema',
                'valor' => 3,
            ],
            [
                'respuesta' => 'Tiene establecido un procedimiento formal de actuación',
                'valor' => 2,
            ],
            [
                'respuesta' => 'No lo sé',
                'valor' => 1,
            ],
            // Pregunta 22
            [
                'respuesta' => 'Muy alta',
                'valor' => 5,
            ],
            [
                'respuesta' => 'Alta',
                'valor' => 4,
            ],
            [
                'respuesta' => 'Media',
                'valor' => 3,
            ],
            [
                'respuesta' => 'Baja',
                'valor' => 2,
            ],
            [
                'respuesta' => 'Muy baja',
                'valor' => 1,
            ],
            // Pregunta 26
            [
                'respuesta' => 'Excesiva',
                'valor' => 5,
            ],
            [
                'respuesta' => 'Elevada',
                'valor' => 4,
            ],

            [
                'respuesta' => 'Escasa',
                'valor' => 2,
            ],
            [
                'respuesta' => 'Muy escasa',
                'valor' => 1,
            ],
            // Pregunta 26
            [
                'respuesta' => 'Excesiva',
                'valor' => 5,
            ],
            [
                'respuesta' => 'Elevada',
                'valor' => 4,
            ],
            [
                'respuesta' => 'Escasa',
                'valor' => 2,
            ],
            [
                'respuesta' => 'Muy escasa',
                'valor' => 1,
            ],
            // Pregunta 37
            [
                'respuesta' => 'No',
                'valor' => 1,
            ],
            [
                'respuesta' => 'A veces',
                'valor' => 2,
            ],
            [
                'respuesta' => 'Bastante',
                'valor' => 3,
            ],
            [
                'respuesta' => 'Mucho',
                'valor' => 4,
            ],

            // Pregunta 38
            [
                'respuesta' => 'Mucho',
                'valor' => 4,
            ],
            [
                'respuesta' => 'Bastante',
                'valor' => 3,
            ],
            [
                'respuesta' => 'Poco',
                'valor' => 2,
            ],
            [
                'respuesta' => 'Nada',
                'valor' => 1,
            ],

            // Pregunta 39
            [
                'respuesta' => 'No es muy importante',
                'valor' => 1,
            ],
            [
                'respuesta' => 'Es importante',
                'valor' => 2,
            ],
            [
                'respuesta' => 'Es muy importante',
                'valor' => 3,
            ],
            [
                'respuesta' => 'No lo sé',
                'valor' => 4, // No hay opción específica
            ],
            // Pregunta 41
            [
                'respuesta' => 'Adecuadamente',
                'valor' => 3,
            ],
            [
                'respuesta' => 'Regular',
                'valor' => 2,
            ],
            [
                'respuesta' => 'Insuficientemente',
                'valor' => 1,
            ],
            [
                'respuesta' => 'No existe posibilidad de desarrollo profesional',
                'valor' => 4, // No hay opción específica
            ],
            // Pregunta 42
            [
                'respuesta' => 'Muy adecuada',
                'valor' => 4,
            ],
            [
                'respuesta' => 'Suficiente',
                'valor' => 3,
            ],
            [
                'respuesta' => 'Insuficiente en algunos casos',
                'valor' => 2,
            ],
            [
                'respuesta' => 'Totalmente insuficiente',
                'valor' => 1,
            ],
        ]);
    }
}
