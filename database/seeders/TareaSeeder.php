<?php

namespace Database\Seeders;

use App\Models\Subtarea;
use App\Models\Tarea;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TareaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tarea::insert([
            [
                'codigo_tarea' => 'TR1',
                'codigo_tarea_cliente' => null,
                'fecha_solicitud' => '20/04/2022',
                'titulo' => 'RUTA MPLS CIRCULAR PALMALES, HINCADO DE POSTE EN RUTA MPLS CIRCULAR PALMALES',
                'para_cliente_proyecto' => Tarea::PARA_PROYECTO,
                'proyecto_id' => 1,
                'coordinador_id' => 3,
                'fiscalizador_id' => 27,
                'cliente_id' => 3,
                'cliente_final_id' => null,
                'observacion' => null,
                'tiene_subtareas' => true,
            ],
            [
                'codigo_tarea' => 'TR2',
                'codigo_tarea_cliente' => 'ERHD69',
                'fecha_solicitud' => '19/04/2022',
                'titulo' => 'MANGA INTERURBANA -SARACAY / HINCADO DE POSTE EN MANGA INTERURBANA-SARACAY',
                'para_cliente_proyecto' => Tarea::PARA_CLIENTE_FINAL,
                'proyecto_id' => null,
                'coordinador_id' => 3,
                'fiscalizador_id' => 27,
                'cliente_id' => 2,
                'cliente_final_id' => 3,
                'observacion' => null,
                'tiene_subtareas' => true,
            ],
            [
                'codigo_tarea' => 'TR3',
                'codigo_tarea_cliente' => 'ERH147',
                'fecha_solicitud' => '19/04/2022',
                'titulo' => 'nodo PORTOVELO y en el Cliente para proceder con el cambio de SPFs y CPE',
                'para_cliente_proyecto' => Tarea::PARA_CLIENTE_FINAL,
                'proyecto_id' => null,
                'coordinador_id' => 3,
                'fiscalizador_id' => 27,
                'cliente_id' => 3,
                'cliente_final_id' => 4,
                'observacion' => null,
                'tiene_subtareas' => true,
            ],
            [
                'codigo_tarea' => 'TR4',
                'codigo_tarea_cliente' => 'ERH147',
                'fecha_solicitud' => '19/04/2022',
                'titulo' => 'Cambio de CPE e interfaces a 10G',
                'observacion' => 'Se necesita llevar laptop y cable de consola a las instalaciones del cliente para brindar acceso remoto al nuevo CPE en caso de ser necesario. Se debe tener personal al mismo tiempo en el cliente y al menos uno de los nodos durante la V/T',
                'para_cliente_proyecto' => Tarea::PARA_CLIENTE_FINAL,
                'proyecto_id' => null,
                'coordinador_id' => 4,
                'fiscalizador_id' => 27,
                'cliente_id' => 3,
                'cliente_final_id' => 4,
                'tiene_subtareas' => false,
            ],
            [
                'codigo_tarea' => 'TR5',
                'codigo_tarea_cliente' => 'ERH188',
                'fecha_solicitud' => '24/01/2023',
                'titulo' => 'Asistencia para la revisión de la UM backup del servicio ECECBEE10096683I',
                'observacion' => 'Asistencia para la revisión de la UM backup del servicio ECECBEE10096683I, se encuentra down y no se recibe potencia en ninguno de los extremos.',
                'tiene_subtareas' => true,
                'para_cliente_proyecto' => Tarea::PARA_CLIENTE_FINAL,
                'proyecto_id' => null,
                'coordinador_id' => 4,
                'fiscalizador_id' => 27,
                'cliente_id' => 3,
                'cliente_final_id' => 4,
            ],
        ]);
    }
}
