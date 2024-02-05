<?php

namespace Database\Seeders;

use App\Models\Medico\Pregunta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PreguntaSeeder extends Seeder
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
                'codigo' =>'1',
                'pregunta' => '¿Trabaja los sábados?',
            ],
            [
                'codigo' =>'2',
                'pregunta' => '¿Trabaja los domingos y festivos?',
            ],
            [
                'codigo' =>'3',
                'pregunta' => '¿Tienes la posibilidad de tomar días u horas libres para atender asuntos de tipo personal?',
            ],
            [
                'codigo' =>'4',
                'pregunta' => '¿Con qué frecuencia tienes que trabajar más tiempo del horario habitual, hacer horas extra o llevarte trabajo a casa?',
            ],
            [
                'codigo' =>'5',
                'pregunta' => '¿Dispones de al menos de 48 horas consecutivas de descanso en el transcurso de una semana (7 días consecutivos)?',
            ],
            [
                'codigo' =>'6',
                'pregunta' => '¿Tu horario laboral te permite compaginar tu tiempo libre (vacaciones, días libres, horario de entrada y salida) con los de tu familia y amigos?',
            ],
            [
                'codigo' =>'7',
                'pregunta' => '¿Puedes decidir cuándo las pausas reglamentarias (pausa comida o bocadillo)?',
            ],
            [
                'codigo' =>'8',
                'pregunta' => 'Durante la jornada de trabajo y fuera de las pautas reglamentarias, ¿puedes detener tu trabajo o hacer una parada corta cuando lo necesitas?',
            ],
            [
                'codigo' =>'9',
                'pregunta' => '¿Puedes marcar tu propio ritmo de trabajo a lo largo de la jornada laboral?',
            ],
            [
                'codigo' =>'10a',
                'pregunta' => '¿Puedes tomar decisiones relativas a: lo que debes hacer (actividad y tareas a realizar?',
            ],
            [
                'codigo' =>'10b',
                'pregunta' => 'Puedes tomar decisiones relativas a: ¿la distribución de tareas a lo largo de tu jornada?',
            ],
            [
                'codigo' =>'10c',
                'pregunta' => 'Puedes tomar decisiones relativas a: la distribución del entorno directo de tu puesto de trabajo (espacio, mobiliario, ¿objetos personales…)',
            ],
            [
                'codigo' =>'10d',
                'pregunta' => '¿Puedes tomar decisiones relativas a: cómo tienes que hacer tu trabajo (método, protocolos, procedimientos de trabajo…)?',
            ],
            [
                'codigo' =>'10e',
                'pregunta' => '¿Puedes tomar decisiones relativas a: la cantidad de trabajo que tienes que realizar?',
            ],
            [
                'codigo' =>'10f',
                'pregunta' => '¿Puedes tomar decisiones relativas a: la calidad del trabajo que realizas?',
            ],
            [
                'codigo' =>'10g',
                'pregunta' => '¿Puedes tomar decisiones relativas a: la reducción de situaciones anormales o incidencias que ocurren en tu trabajo?',
            ],
            [
                'codigo' =>'10h',
                'pregunta' => '¿Puedes tomar decisiones relativas a: la distribución de los turnos rotativos?',
            ],
            [
                'codigo' =>'11a',
                'pregunta' => '¿Qué nivel de participación tienes en los siguientes aspectos de tu trabajo: introducción de cambios en los equipos y materiales?',
            ],
            [
                'codigo' =>'11b',
                'pregunta' => '¿Qué nivel de participación tienes en los siguientes aspectos de tu trabajo: introducción de cambios en la manera de trabajar?',
            ],
            [
                'codigo' =>'11c',
                'pregunta' => '¿Qué nivel de participación tienes en los siguientes aspectos de tu trabajo: lanzamiento de nuevos o mejores productos o servicios?',
            ],
            [
                'codigo' =>'11d',
                'pregunta' => '¿Qué nivel de participación tienes en los siguientes aspectos de tu trabajo: reestructuración o reorganización de departamentos o áreas de trabajo?',
            ],
            [
                'codigo' =>'11e',
                'pregunta' => '¿Qué nivel de participación tienes en los siguientes aspectos de tu trabajo: cambios en la dirección o entre tus superiores?',
            ],
            [
                'codigo' =>'11f',
                'pregunta' => '¿Qué nivel de participación tienes en los siguientes aspectos de tu trabajo: contratación o incorporación de nuevos empleados?',
            ],
            [
                'codigo' =>'11g',
                'pregunta' => '¿Qué nivel de participación tienes en los siguientes aspectos de tu trabajo: elaboración de las normas de trabajo?',
            ],
            [
                'codigo' =>'12a',
                'pregunta' => '¿Cómo valoras la supervisión que tu responsable inmediato ejerce sobre los siguientes aspectos de tu trabajo? El método para realizar el trabajo.',
            ],
            [
                'codigo' =>'12b',
                'pregunta' => '¿Cómo valoras la supervisión que tu responsable inmediato ejerce sobre los siguientes aspectos de tu trabajo? La planificación del trabajo',
            ],
            [
                'codigo' =>'12c',
                'pregunta' => '¿Cómo valoras la supervisión que tu responsable inmediato ejerce sobre los siguientes aspectos de tu trabajo? El ritmo de trabajo',
            ],
            [
                'codigo' =>'12d',
                'pregunta' => '¿Cómo valoras la supervisión que tu responsable inmediato ejerce sobre los siguientes aspectos de tu trabajo? La calidad del trabajo realizado',
            ],
            [
                'codigo' =>'13a',
                'pregunta' => '¿Cómo valoras el grado de información que te proporciona la empresa sobre los siguientes aspectos? Las posibilidades de formación',
            ],
            [
                'codigo' =>'13b',
                'pregunta' => '¿Cómo valoras el grado de información que te proporciona la empresa sobre los siguientes aspectos? Las posibilidades de promoción',
            ],
            [
                'codigo' =>'13c',
                'pregunta' => '¿Cómo valoras el grado de información que te proporciona la empresa sobre los siguientes aspectos? Los requisitos para ocupar plazas de promoción',
            ],
            [
                'codigo' =>'13d',
                'pregunta' => '¿Como valoras el grado de información que te proporciona la empresa sobre los siguientes aspectos? La situación de la empresa en el mercado',
            ],
            [
                'codigo' =>'14a',
                'pregunta' => 'Para realizar tu trabajo, ¿cómo valoras la información que recibes sobre los siguientes aspectos? Lo que debes hacer (funciones, competencias y atribuciones).',
            ],
            [
                'codigo' =>'14b',
                'pregunta' => 'Para realizar tu trabajo, ¿cómo valoras la información que recibes sobre los siguientes aspectos? Cómo debes hacerlo (métodos, protocolos, procedimientos de trabajo).',
            ],
        ]);
    }
}
