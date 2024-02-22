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
            [
                'codigo' =>'14d',
                'pregunta' => 'Para realizar tu trabajo, ¿cómo valoras la información que recibes sobre los siguientes aspectos? La calidad del trabajo que se espera que hagas',
            ],
            [
                'codigo' =>'14e',
                'pregunta' => 'Para realizar tu trabajo, ¿cómo valoras la Información que recibes sobre los siguientes aspectos? El tiempo asignado para realizar el trabajo',
            ],
            [
                'codigo' =>'14f',
                'pregunta' => 'Para realizar tu trabajo, ¿cómo valoras la información que recibes sobre los siguientes aspectos? La responsabilidad del puesto de trabajo (qué errores o defectos pueden achacarse a tu actuación y cuáles no)',
            ],
            [
                'codigo' =>'15a',
                'pregunta' => 'Señala con qué frecuencia se dan las siguientes situaciones en tu trabajo: se te asignan tareas que no puedes realizar por no tener los recursos humanos o materiales',
            ],
            [
                'codigo' =>'15b',
                'pregunta' => 'Señala con qué frecuencia se dan las siguientes situaciones en tu trabajo: para ejecutar algunas tareas tienes que saltarte los métodos establecidos',
            ],
            [
                'codigo' =>'15c',
                'pregunta' => 'Señala con qué frecuencia se dan las siguientes situaciones en tu trabajo: se te exige tomar decisiones o realizar cosas con las que no estás de acuerdo porque te suponen un conflicto moral, legal, emocional...',
            ],
            [
                'codigo' =>'15d',
                'pregunta' => 'Señala con qué frecuencia se dan las siguientes situaciones en tu trabajo: recibes instrucciones contradictorias entre sí (unos te mandan una cosa y otros otra)',
            ],
            [
                'codigo' =>'15e',
                'pregunta' => 'Señala con qué frecuencia se dan las siguientes situaciones en tu trabajo: se te exigen responsabilidades, cometidos o tareas que no entran dentro de tus funciones y que deberían llevar a cabo otros trabajadores',
            ],
            [
                'codigo' =>'16a',
                'pregunta' => 'Si tienes que realizar un trabajo delicado o complicado y deseas ayuda o apoyo, puedes contar con: tus superiores',
            ],
            [
                'codigo' =>'16b',
                'pregunta' => 'Si tienes que realizar un trabajo delicado o complicado y deseas ayuda o apoyo, puedes contar con: tus compañeros',
            ],
            [
                'codigo' =>'16c',
                'pregunta' => 'Si tienes que realizar un trabajo delicado o complicado y deseas ayuda o apoyo, puedes contar con: tus subordinados',
            ],
            [
                'codigo' =>'16d',
                'pregunta' => 'Si tienes que realizar un trabajo delicado o complicado y deseas ayuda o apoyo, puedes contar con: otras personas que trabajan en la empresa',
            ],
            [
                'codigo' =>'17',
                'pregunta' => '¿Cómo consideras que son las relaciones con las personas con las que debes trabajar?',
            ],
            [
                'codigo' =>'18a',
                'pregunta' => 'Con qué frecuencia se producen en tu trabajo: los conflictos Interpersonales',
            ],
            [
                'codigo' =>'18b',
                'pregunta' => 'Con qué frecuencia se producen en tu trabajo: las situaciones de violencia física',
            ],
            [
                'codigo' =>'18c',
                'pregunta' => 'Con qué frecuencia se producen en tu trabajo: las situaciones de violencia psicológica (amenazas, insultos, hacer el vacío, descalificaciones personales...)',
            ],
            [
                'codigo' =>'18d',
                'pregunta' => 'Con qué frecuencia se producen en tu trabajo: las situaciones de acoso sexual',
            ],
            [
                'codigo' =>'19',
                'pregunta' => 'Tu empresa, frente a situaciones de conflicto interpersonal entre trabajadores:',
            ],
            [
                'codigo' =>'20',
                'pregunta' => '¿En tu entorno laboral, te sientes discriminado/a (por razones de edad, sexo, religión, raza, formación, categoría...)?',
            ],
            [
                'codigo' =>'21',
                'pregunta' => 'A lo largo de la jornada, ¿Cuánto tiempo debes mantener una exclusiva atención en tu trabajo (de forma que te impida hablar, desplazarte o, simplemente, pensar en cosas ajenas a tu tarea)?',
            ],
            [
                'codigo' =>'22',
                'pregunta' => 'En general, ¿Cómo consideras la atención que debes mantener para realizar tu trabajo?',
            ],
            [
                'codigo' =>'23',
                'pregunta' => '¿El tiempo de que dispones para realizar tu trabajo es suficiente y adecuado?',
            ],
            [
                'codigo' =>'24',
                'pregunta' => '¿La ejecución de tu tarea te impone trabajar con rapidez?',
            ],
            [
                'codigo' =>'25',
                'pregunta' => '¿Con qué frecuencia debes acelerar el ritmo de trabajo?',
            ],
            [
                'codigo' =>'26',
                'pregunta' => 'En general, la cantidad de trabajo que tienes es:',
            ],
            [
                'codigo' =>'27',
                'pregunta' => '¿Debes atender a varias tareas al mismo tiempo?',
            ],
            [
                'codigo' =>'28',
                'pregunta' => 'El trabajo que realizas, ¿te resulta complicado o difícil?',
            ],
            [
                'codigo' =>'29',
                'pregunta' => 'En tu trabajo, ¿tienes que llevar a cabo tareas tan difíciles que necesitas pedir a alguien consejo o ayuda?',
            ],
            [
                'codigo' =>'30',
                'pregunta' => 'En tu trabajo, ¿tienes que interrumpir la tarea que estás haciendo para realizar otra no prevista?',
            ],
            [
                'codigo' =>'31',
                'pregunta' => 'En el caso de que existan interrupciones, ¿alteran seriamente la ejecución de tu trabajo?',
            ],
            [
                'codigo' =>'32',
                'pregunta' => '¿La cantidad de trabajo que tienes suele ser irregular e imprevisible?',
            ],
            [
                'codigo' =>'33a',
                'pregunta' => 'En qué medida tu trabajo requiere: aprender cosas o métodos nuevos',
            ],
            [
                'codigo' =>'33b',
                'pregunta' => 'En qué medida tu trabajo requiere: adaptarse a nuevas situaciones',
            ],
            [
                'codigo' =>'33c',
                'pregunta' => 'En qué medida tu trabajo requiere: tomar iniciativas',
            ],
            [
                'codigo' =>'33d',
                'pregunta' => 'En qué medida tu trabajo requiere: tener buena memoria',
            ],
            [
                'codigo' =>'33e',
                'pregunta' => 'En qué medida tu trabajo requiere: ser creativo',
            ],
            [
                'codigo' =>'33f',
                'pregunta' => 'En qué medida tu trabajo requiere: tratar directamente con personas que no están empleadas en tu trabajo (clientes, pasajeros, alumnos, pacientes...)',
            ],
            [
                'codigo' =>'34a',
                'pregunta' => 'En tu trabajo, ¿con qué frecuencia tienes que ocultar tus emociones y sentimientos ante...? Tus superiores jerárquicos',
            ],
            [
                'codigo' =>'34b',
                'pregunta' => 'En tu trabajo, ¿con qué frecuencia tienes que ocultar tus emociones y sentimientos ante...? Tus subordinados',
            ],
            [
                'codigo' =>'34c',
                'pregunta' => 'En tu trabajo, ¿con qué frecuencia tienes que ocultar tus emociones y sentimientos ante...? Tus compañeros de trabajo',
            ],
            [
                'codigo' =>'34d',
                'pregunta' => '¿En tu trabajo, ¿con que frecuencia tienes que ocultar tus emociones y sentimientos ante...? Personas que no están empleadas en la empresa (clientes, pasajeros, alumnos, pacientes...)',
            ],
            [
                'codigo' =>'35',
                'pregunta' => 'Por el tipo de trabajo que tienes, ¿estás expuesto/a situaciones que te afectan emocionalmente?',
            ],
            [
                'codigo' =>'36',
                'pregunta' => '¿Por el tipo de trabajo que tienes, ¿con qué frecuencia se espera que des una respuesta a los problemas emocionales y personales de tus clientes externos? (pasajeros, alumnos, pacientes, etc.):',
            ],
            [
                'codigo' =>'37',
                'pregunta' => 'El trabajo que realizas, te resulta rutinario?',
            ],
            [
                'codigo' =>'38',
                'pregunta' => 'En general, ¿consideras que las tareas que realizas tienen sentido?',
            ],
            [
                'codigo' =>'39',
                'pregunta' => '¿Cómo contribuye tu trabajo en el conjunto de la empresa u organización?',
            ],
            [
                'codigo' =>'40a',
                'pregunta' => '¿En general, está tu trabajo reconocido y apreciado por...? Tus superiores',
            ],
            [
                'codigo' =>'40b',
                'pregunta' => '¿En general, está tu trabajo reconocido y apreciado por...? Tus compañeros de trabajo',
            ],
            [
                'codigo' =>'40c',
                'pregunta' => '¿En general, está tu trabajo reconocido y apreciado por…? El público, clientes, pasajeros, alumnos, pacientes... (si los hay)',
            ],
            [
                'codigo' =>'40d',
                'pregunta' => '¿En general, está tu trabajo reconocido y apreciado por...? Tu familia y tus amistades',
            ],
            [
                'codigo' =>'41',
                'pregunta' => 'Te facilita la empresa el desarrollo profesional (promoción, plan de carrera, ¿etc.)?',
            ],
            [
                'codigo' =>'42',
                'pregunta' => '¿Como definirías la formación que se imparte o se facilita desde tu empresa?',
            ],
            [
                'codigo' =>'43',
                'pregunta' => 'En general, la correspondencia entre el esfuerzo que haces y las recompensas que la empresa te proporciona es:',
            ],
            [
                'codigo' =>'44',
                'pregunta' => 'Considerando los deberes y responsabilidades de tu trabajo, ¿estás satisfecho/a con el salario que recibes?',
            ],
        ]);
    }
}
