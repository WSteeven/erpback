<?php

namespace Database\Seeders;

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
                'codigo_tarea_jp' => 'JP000001',
                'codigo_tarea_cliente' => 'ADD878',
                'cliente_id' => 1,
                'coordinador_id' => 2,
                'fecha_solicitud' => '2022/08/30 00:00:00',
                'fecha_inicio' => '2022/08/11 00:00:00',
                'fecha_finalizacion' => '2022/08/31 00:00:00',
                'solicitante' => 'JOAQUIN PIZARRO',
                'correo_solicitante' => 'JPIZARRO@NEDETEL.EC',
                'detalle' => 'RUTA MPLS CIRCULAR PALMALES, HINCADO DE POSTE EN RUTA MPLS CIRCULAR PALMALES',
                'es_proyecto' => false,
                /*'estado' => 'FINALIZADO',
                'provincia' => 'EL ORO',
                'ciudad' => 'MACHALA',
                'parroquia' => 'JAMBELÍ',
                'referencias' => '',
                'direccion' => 'MI CASA',
                'georeferencia_x' => '0145 855',
                'georeferencia_y' => '425 785', */
            ]
        ]);
    }
}
