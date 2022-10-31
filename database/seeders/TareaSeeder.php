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
                'codigo_tarea' => '000000001',
                'codigo_tarea_cliente' => 'ADD878',
                'cliente_id' => 2,
                'coordinador_id' => 3,
                'supervisor_id' => 3,
                'cliente_final_id' => 1,
                'detalle' => 'RUTA MPLS CIRCULAR PALMALES, HINCADO DE POSTE EN RUTA MPLS CIRCULAR PALMALES',
                'es_proyecto' => false,
                'fecha_solicitud' => '20/04/2022',
                'hora_solicitud' => '12:35:00',
                /*'estado' => 'FINALIZADO',
                'provincia' => 'EL ORO',
                'ciudad' => 'MACHALA',
                'parroquia' => 'JAMBELÍ',
                'referencias' => '',
                'direccion' => 'MI CASA',
                'georeferencia_x' => '0145 855',
                'georeferencia_y' => '425 785', */
            ],
            [
                'codigo_tarea' => '000000002',
                'codigo_tarea_cliente' => 'ERHD69',
                'cliente_id' => 3,
                'coordinador_id' => 3,
                'supervisor_id' => 3,
                'cliente_final_id' => 1,
                'detalle' => 'MANGA INTERURBANA -SARACAY / HINCADO DE POSTE EN MANGA INTERURBANA-SARACAY',
                'es_proyecto' => false,
                'fecha_solicitud' => '19/04/2022',
                'hora_solicitud' => '10:14:00',
                /*'estado' => 'FINALIZADO',
                'provincia' => 'EL ORO',
                'ciudad' => 'MACHALA',
                'parroquia' => 'JAMBELÍ',
                'referencias' => '',
                'direccion' => 'MI CASA',
                'georeferencia_x' => '0145 855',
                'georeferencia_y' => '425 785', */
            ],
        ]);
    }
}
