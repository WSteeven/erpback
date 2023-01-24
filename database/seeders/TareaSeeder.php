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
                'detalle' => 'RUTA MPLS CIRCULAR PALMALES, HINCADO DE POSTE EN RUTA MPLS CIRCULAR PALMALES',
                'estado' => Subtarea::ASIGNADO,
                'destino' => Tarea::PARA_PROYECTO,
                'proyecto_id' => 1,
                'coordinador_id' => 3,
                'supervisor_id' => 24,
                'cliente_id' => null,
                'cliente_final_id' => null,
                'observacion' => null,
            ],
            [
                'codigo_tarea' => 'TR2',
                'codigo_tarea_cliente' => 'ERHD69',
                'fecha_solicitud' => '19/04/2022',
                'detalle' => 'MANGA INTERURBANA -SARACAY / HINCADO DE POSTE EN MANGA INTERURBANA-SARACAY',
                'estado' => Subtarea::ASIGNADO,
                'destino' => Tarea::PARA_CLIENTE_FINAL,
                'proyecto_id' => null,
                'coordinador_id' => 3,
                'supervisor_id' => 24,
                'cliente_id' => 2,
                'cliente_final_id' => 3,
                'observacion' => null,
            ],
            [
                'codigo_tarea' => 'TR3',
                'codigo_tarea_cliente' => 'ERH147',
                'fecha_solicitud' => '19/04/2022',
                'detalle' => 'Se necesita personal en el nodo PORTOVELO y en el Cliente para proceder con el cambio de SPFs y CPE en Ventana programada. Se puede proceder con el cambio de SFPs de manera simultanea, cliente tiene una única UM.',
                'estado' => Subtarea::CREADO,
                'destino' => Tarea::PARA_CLIENTE_FINAL,
                'proyecto_id' => null,
                'coordinador_id' => 3,
                'supervisor_id' => 24,
                'cliente_id' => 3,
                'cliente_final_id' => 4,
                'observacion' => null,
            ],
            [
                'codigo_tarea' => 'TR4',
                'codigo_tarea_cliente' => 'ERH147',
                'fecha_solicitud' => '19/04/2022',
                'detalle' => 'Ventana de trabajo de Cambio de CPE e interfaces a 10G',
                'observacion' => 'Se necesita llevar laptop y cable de consola a las instalaciones del cliente para brindar acceso remoto al nuevo CPE en caso de ser necesario. Se debe tener personal al mismo tiempo en el cliente y al menos uno de los nodos durante la V/T',
                'estado' => Subtarea::CREADO,
                'destino' => Tarea::PARA_CLIENTE_FINAL,
                'proyecto_id' => null,
                'coordinador_id' => 3,
                'supervisor_id' => 24,
                'cliente_id' => 3,
                'cliente_final_id' => 4,
                'observacion' => null,
            ],
            [
                'codigo_tarea' => 'TR5',
                'codigo_tarea_cliente' => 'ERH188',
                'fecha_solicitud' => '24/01/2023',
                'detalle' => 'Asistencia para la revisión de la UM bakcup del servicio ECECBEE10096683I, se encuentra down y no se recibe potencia en ninguno de los extremos.',
                'observacion' => null,
                'estado' => Subtarea::CREADO,
                'destino' => Tarea::PARA_CLIENTE_FINAL,
                'proyecto_id' => null,
                'coordinador_id' => 3,
                'supervisor_id' => 24,
                'cliente_id' => 3,
                'cliente_final_id' => 4,
                'observacion' => null,
            ],
        ]);
    }
}
