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
                'detalle' => 'RUTA MPLS CIRCULAR PALMALES, HINCADO DE POSTE EN RUTA MPLS CIRCULAR PALMALES',
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
            ]
        ]);
    }
}
