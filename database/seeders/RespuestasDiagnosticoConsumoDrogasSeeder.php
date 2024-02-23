<?php

namespace Database\Seeders;

use App\Models\Medico\Respuesta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RespuestasDiagnosticoConsumoDrogasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Respuesta::insert([
            //Pregunta1
            [
                'respuesta' => 'Alchol',//67
                'valor' => 'Alchol',
            ],
            [
                'respuesta' => 'Tabaco',//68
                'valor' => 'Tabaco',
            ],
            [
                'respuesta' => 'Anfetaminas',//69
                'valor' => 'Anfetaminas',
            ],
            [
                'respuesta' => 'No Consume',//70
                'valor' => 'No Consume',
            ],
            [
                'respuesta' => 'Otros',//71
                'valor' => 'Otros',
            ],
            //Pregunta 4
            [
                'respuesta' => '2 a 4 días de la semana ',//72
                'valor' => '2 a 4 días de la semana ',
            ],
            [
                'respuesta' => '2 a 7 días de la semana ',//73
                'valor' => '2 a 7 días de la semana ',
            ], [
                'respuesta' => '2 a 12 días de la semana ',//74
                'valor' => '2 a 12 días de la semana ',
            ],
            [
                'respuesta' => '5 a 7 días de la semana ',//75
                'valor' => ' 5 a 7 días de la semana ',
            ],
            [
                'respuesta' => '1 vez al año ',//76
                'valor' => ' 1 vez al año ',
            ],
            [
                'respuesta' => 'no consume ',//77
                'valor' => 'no consume ',
            ],
            //Pregunta 5
            [
                'respuesta' => 'SI',//78
                'valor' => 'SI',
            ],
            [
                'respuesta' => 'NO',//79
                'valor' => 'NO',
            ],
            [
                'respuesta' => 'NO APLICA',//80
                'valor' => 'NO APLICA',
            ],
            //Pregunta 6
            [
                'respuesta' => 'AGOBIO Y TENSION EN TRABAJO',//81
                'valor' => 'AGOBIO Y TENSION EN TRABAJO',
            ],
            [
                'respuesta' => 'COMPAÑEROS CONSUMIDORES',//82
                'valor' => 'COMPAÑEROS CONSUMIDORES',
            ],
            [
                'respuesta' => 'CANSANCIO INTESO,AGOBIO',//83
                'valor' => 'CANSANCIO INTESO,AGOBIO',
            ],
            [
                'respuesta' => 'ACOSO LABORAL',//84
                'valor' => 'ACOSO LABORAL',
            ],
            [
                'respuesta' => 'CURIOSIDAD SOBRE EL EFECTO DE LAS DROGAS',//85
                'valor' => 'CURIOSIDAD SOBRE EL EFECTO DE LAS DROGAS',
            ],
            [
                'respuesta' => 'DIFICULTAD DE RESOLUCION DE PROBLEMAS',//86
                'valor' => 'DIFICULTAD DE RESOLUCION DE PROBLEMAS',
            ],
            [
                'respuesta' => 'ELEVADOS NIVELES DE TENSION Y ESTRÉS LABORALES',//87
                'valor' => 'ELEVADOS NIVELES DE TENSION Y ESTRÉS LABORALES',
            ],
            [
                'respuesta' => 'FAMILIARES CONSUMIDORES',//88
                'valor' => 'FAMILIARES CONSUMIDORES',
            ],
            [
                'respuesta' => 'LARGAS AUSENCIAS DEL HOGAR POR MOTIVOS LABORALES',//89
                'valor' => 'LARGAS AUSENCIAS DEL HOGAR POR MOTIVOS LABORALES',
            ],
            [
                'respuesta' => 'MALA SITUACION EN LA FAMILIA',//90
                'valor' => 'MALA SITUACION EN LA FAMILIA',
            ],
            [
                'respuesta' => 'TAREAS RUTINARIAS Y MONOTONAS',//91
                'valor' => 'TAREAS RUTINARIAS Y MONOTONAS',
            ],
            [
                'respuesta' => 'TRATO QUE RECIBE DE LOS SUPERIORES Y COMPAÑEROS',//92
                'valor' => 'TRATO QUE RECIBE DE LOS SUPERIORES Y COMPAÑEROS',
            ],
        ]);
    }
}
