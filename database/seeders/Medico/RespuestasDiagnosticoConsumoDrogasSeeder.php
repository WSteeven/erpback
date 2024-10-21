<?php

namespace Database\Seeders\RecursosHumanos\Medico;

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
            //Pregunta 1 y 3
            [
                'respuesta' => 'Alcohol', //69
                'valor' => 'Alcohol',
            ],
            [
                'respuesta' => 'Tabaco', // 70
                'valor' => 'Tabaco',
            ],
            [
                'respuesta' => 'Anfetaminas', // 71
                'valor' => 'Anfetaminas',
            ],
            [
                'respuesta' => 'No Consume', // 72
                'valor' => 'No Consume',
            ],
            [
                'respuesta' => 'Otros', // 73
                'valor' => 'Otros',
            ],
            //Pregunta 4
            [
                'respuesta' => '5 a 7 días de la semana ', //74
                'valor' => ' 5 a 7 días de la semana ',
            ],
            [
                'respuesta' => '2 a 4 VECES A la semana ', //75
                'valor' => '2 a 4 días de la semana ',
            ],
            [
                'respuesta' => '2 a 7 VECES A la semana ', //76
                'valor' => '2 a 7 días de la semana ',
            ],
            [
                'respuesta' => 'Al menos una vez a la semana', //77
                'valor' => 'AL MENOS UNA VEZ A LA SEMANA',
            ],
            [
                'respuesta' => '2 a 12 veces al año', //78
                'valor' => '2 a 12 VECES AL AÑO',
            ],
            [
                'respuesta' => '1 vez al año', //79
                'valor' => ' 1 VEZ AL AÑO',
            ],
            [
                'respuesta' => 'No consume', // 80
                'valor' => 'NO CONSUME',
            ],
            //Pregunta 5
            [
                'respuesta' => 'SI', // 81
                'valor' => 'SI',
            ],
            [
                'respuesta' => 'NO', // 82
                'valor' => 'NO',
            ],
            [
                'respuesta' => 'NO APLICA', //83
                'valor' => 'NO APLICA',
            ],
            //Pregunta 6
            [
                'respuesta' => 'AGOBIO Y TENSION EN TRABAJO', //84
                'valor' => 'AGOBIO Y TENSION EN TRABAJO',
            ],
            [
                'respuesta' => 'COMPAÑEROS CONSUMIDORES', //85
                'valor' => 'COMPAÑEROS CONSUMIDORES',
            ],
            [
                'respuesta' => 'CANSANCIO INTENSO,AGOBIO', //86
                'valor' => 'CANSANCIO INTENSO,AGOBIO',
            ],
            [
                'respuesta' => 'ACOSO LABORAL', //87
                'valor' => 'ACOSO LABORAL',
            ],
            [
                'respuesta' => 'CURIOSIDAD SOBRE EL EFECTO DE LAS DROGAS', //88
                'valor' => 'CURIOSIDAD SOBRE EL EFECTO DE LAS DROGAS',
            ],
            [
                'respuesta' => 'DIFICULTAD DE RESOLUCION DE PROBLEMAS', //89
                'valor' => 'DIFICULTAD DE RESOLUCION DE PROBLEMAS',
            ],
            [
                'respuesta' => 'ELEVADOS NIVELES DE TENSION Y ESTRÉS LABORALES', // 90
                'valor' => 'ELEVADOS NIVELES DE TENSION Y ESTRÉS LABORALES',
            ],
            [
                'respuesta' => 'FAMILIARES CONSUMIDORES', // 91
                'valor' => 'FAMILIARES CONSUMIDORES',
            ],
            [
                'respuesta' => 'LARGAS AUSENCIAS DEL HOGAR POR MOTIVOS LABORALES', // 92
                'valor' => 'LARGAS AUSENCIAS DEL HOGAR POR MOTIVOS LABORALES',
            ],
            [
                'respuesta' => 'MALA SITUACION EN LA FAMILIA', //93
                'valor' => 'MALA SITUACION EN LA FAMILIA',
            ],
            [
                'respuesta' => 'TAREAS RUTINARIAS Y MONOTONAS', //94
                'valor' => 'TAREAS RUTINARIAS Y MONOTONAS',
            ],
            [
                'respuesta' => 'TRATO QUE RECIBE DE LOS SUPERIORES Y COMPAÑEROS', // 95
                'valor' => 'TRATO QUE RECIBE DE LOS SUPERIORES Y COMPAÑEROS',
            ],
        ]);
    }
}
