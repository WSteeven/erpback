<?php

namespace Database\Seeders;

use App\Models\Subtarea;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubtareaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Subtarea::insert([
            [
                'codigo_subtarea' => 'JP000001_1',
                'detalle' => 'HINCADO DE POSTE EN RUTA MPLS CIRCULAR PALMALES',
                'fecha_solicitud' => '2022/08/30 00:00:00',
                'fecha_inicio' => '2022/08/11 00:00:00',
                'fecha_finalizacion' => '2022/08/31 00:00:00',
                'actividad_realizada' => '',
                'novedades' => '',
                'fiscalizador' => 'FERNANDO COELLO',
                'ing_soporte' => 'ADRIAN BUSTOS',
                'ing_instalacion' => 'CARLOS DUARTE',
                'tipo_instalacion' => '',
                'id_servicio' => 'DJD8452',
                'ticket_phoenix' => '',
                'tipo_tarea_id' => 1,
                'tarea_id' => 1,
            ],
            [
                'codigo_subtarea' => 'JP000001_2',
                'detalle' => 'TENDIDO FTTH EN RUTA MPLS CIRCULAR PALMALES',
                'fecha_solicitud' => '2022/09/3 00:00:00',
                'fecha_inicio' => '2022/09/4 00:00:00',
                'fecha_finalizacion' => '2022/09/8 00:00:00',
                'actividad_realizada' => '',
                'novedades' => '',
                'fiscalizador' => 'FERNANDO COELLO',
                'ing_soporte' => 'ADRIAN BUSTOS',
                'ing_instalacion' => 'CARLOS DUARTE',
                'tipo_instalacion' => '',
                'id_servicio' => 'DJD8452',
                'ticket_phoenix' => '',
                'tipo_tarea_id' => 2,
                'tarea_id' => 1,
            ],
            [
                'codigo_subtarea' => 'JP000002_1',
                'detalle' => 'MANGA INTERURBANA -SARACAY',
                'fecha_solicitud' => '2022/09/3 00:00:00',
                'fecha_inicio' => '2022/09/4 00:00:00',
                'fecha_finalizacion' => '2022/09/8 00:00:00',
                'actividad_realizada' => '',
                'novedades' => '',
                'fiscalizador' => 'FERNANDO COELLO',
                'ing_soporte' => 'ADRIAN BUSTOS',
                'ing_instalacion' => 'CARLOS DUARTE',
                'tipo_instalacion' => '',
                'id_servicio' => 'DJD8EYT',
                'ticket_phoenix' => '',
                'tipo_tarea_id' => 1,
                'tarea_id' => 2,
            ]
        ]);
    }
}
