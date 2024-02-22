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
            [
                'respuesta' => 'Alchol',
                'valor' => 'Alchol',
            ],
            [
                'respuesta' => 'Tabaco',
                'valor' => 'Tabaco',
            ],
            [
                'respuesta' => 'Anfetaminas',
                'valor' => 'Anfetaminas',
            ],
            [
                'respuesta' => 'No Consume',
                'valor' => 'No Consume',
            ],
            [
                'respuesta' => 'Otros',
                'valor' => 'Otros',
            ],
            [
                'respuesta' => '2 a 4 días de la semana ',
                'valor' => '2 a 4 días de la semana ',
            ],
            [
                'respuesta' => '2 a 7 días de la semana ',
                'valor' => '2 a 7 días de la semana ',
            ], [
                'respuesta' => '2 a 12 días de la semana ',
                'valor' => '2 a 12 días de la semana ',
            ],
            [
                'respuesta' => '5 a 7 días de la semana ',
                'valor' => ' 5 a 7 días de la semana ',
            ],
            [
                'respuesta' => '1 vez al año ',
                'valor' => ' 1 vez al año ',
            ],
            [
                'respuesta' => 'no consume ',
                'valor' => 'no consume ',
            ],
            [
                'respuesta' => 'SI',
                'valor' => 'SI',
            ],
            [
                'respuesta' => 'NO',
                'valor' => 'NO',
            ],
            [
                'respuesta' => 'NO APLICA',
                'valor' => 'NO APLICA',
            ],
            [
                'respuesta' => 'AGOBIO Y TENSION EN TRABAJO',
                'valor' => 'AGOBIO Y TENSION EN TRABAJO',
            ],
            [
                'respuesta' => 'COMPAÑEROS CONSUMIDORES',
                'valor' => 'COMPAÑEROS CONSUMIDORES',
            ],
            [
                'respuesta' => 'CANSANCIO INTESO,AGOBIO',
                'valor' => 'CANSANCIO INTESO,AGOBIO',
            ],
            [
                'respuesta' => 'ACOSO LABORAL',
                'valor' => 'ACOSO LABORAL',
            ],
            [
                'respuesta' => 'CURIOSIDAD SOBRE EL EFECTO DE LAS DROGAS',
                'valor' => 'CURIOSIDAD SOBRE EL EFECTO DE LAS DROGAS',
            ],
            [
                'respuesta' => 'DIFICULTAD DE RESOLUCION DE PROBLEMAS',
                'valor' => 'DIFICULTAD DE RESOLUCION DE PROBLEMAS',
            ],
            [
                'respuesta' => 'ELEVADOS NIVELES DE TENSION Y ESTRÉS LABORALES',
                'valor' => 'ELEVADOS NIVELES DE TENSION Y ESTRÉS LABORALES',
            ],
            [
                'respuesta' => 'FAMILIARES CONSUMIDORES',
                'valor' => 'FAMILIARES CONSUMIDORES',
            ],
            [
                'respuesta' => 'LARGAS AUSENCIAS DEL HOGAR POR MOTIVOS LABORALES',
                'valor' => 'LARGAS AUSENCIAS DEL HOGAR POR MOTIVOS LABORALES',
            ],
            [
                'respuesta' => 'MALA SITUACION EN LA FAMILIA',
                'valor' => 'MALA SITUACION EN LA FAMILIA',
            ],
            [
                'respuesta' => 'TAREAS RUTINARIAS Y MONOTONAS',
                'valor' => 'TAREAS RUTINARIAS Y MONOTONAS',
            ],
            [
                'respuesta' => 'TRATO QUE RECIBE DE LOS SUPERIORES Y COMPAÑEROS',
                'valor' => 'TRATO QUE RECIBE DE LOS SUPERIORES Y COMPAÑEROS',
            ],
        ]);
    }
}
