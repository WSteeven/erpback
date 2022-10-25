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
                'codigo_subtarea' => '00000001-1',
                'detalle' => 'HINCADO DE POSTE EN RUTA MPLS CIRCULAR PALMALES',
                'fecha_hora_creacion' => '2022/08/30 00:00:00',
                'fecha_hora_asignacion' => '2022/08/30 00:00:00',
                'cantidad_dias' => 2,
                'fecha_hora_inicio' => '2022/08/11 00:00:00',
                'fecha_hora_finalizacion' => '2022/08/31 00:00:00',
                'fecha_hora_realizado' => '2022/08/31 00:00:00',
                'fecha_hora_suspendido' => '2022/08/31 00:00:00',
                'causa_suspencion' => 'CLIENTE NO RESPONSE A LAS LLAMADAS',
                'fecha_hora_cancelacion' => '',
                'causa_cancelacion' => '',
                'es_dependiente' => false,
                'subtarea_dependiente' => null,
                'es_ventana' => false,
                'hora_inicio_ventana' => null,
                'hora_fin_ventana' => null,
                'descripcion_completa' => '',
                'tecnicos_grupo_principal' => '[1, 2, 3]',
                'tecnicos_otros_grupos' => '[4, 5]',
                'estado' => 'EN EJECUCION',
                'tipo_trabajo_id' => 1,
                'tarea_id' => 1,
                'grupo_id' => 1,
            ],
            [
                'codigo_subtarea' => '00000001-2',
                'detalle' => 'TENDIDO FTTH EN RUTA MPLS CIRCULAR PALMALES',
                'fecha_hora_creacion' => '2022/09/3 00:00:00',
                'fecha_hora_asignacion' => '2022/09/3 00:00:00',
                'fecha_hora_inicio' => '2022/09/4 00:00:00',
                'fecha_hora_finalizacion' => '2022/09/8 00:00:00',
                'fecha_hora_realizado' => '2022/09/8 00:00:00',
                'fecha_hora_suspendido' => '2022/09/8 00:00:00',
                'causa_suspencion' => 'CLIENTE NO RESPONSE A LAS LLAMADAS',
                'fecha_hora_cancelacion' => '',
                'causa_cancelacion' => '',
                'es_dependiente' => false,
                'subtarea_dependiente' => null,
                'es_ventana' => false,
                'hora_inicio_ventana' => null,
                'hora_fin_ventana' => null,
                'descripcion_completa' => '',
                'tecnicos_grupo_principal' => '[1, 2, 3]',
                'tecnicos_otros_grupos' => '[4, 5]',
                'estado' => 'EN EJECUCION',
                'cantidad_dias' => 3,
                'tipo_trabajo_id' => 2,
                'tarea_id' => 1,
                'grupo_id' => 1,
            ],
            [
                'codigo_subtarea' => '00000002-1',
                'detalle' => 'MANGA INTERURBANA -SARACAY',
                'fecha_hora_creacion' => '2022/09/3 00:00:00',
                'fecha_hora_asignacion' => '2022/09/3 00:00:00',
                'fecha_hora_inicio' => '2022/09/4 00:00:00',
                'fecha_hora_finalizacion' => '2022/09/8 00:00:00',
                'fecha_hora_realizado' => '2022/09/8 00:00:00',
                'fecha_hora_suspendido' => '2022/09/8 00:00:00',
                'causa_suspencion' => 'CLIENTE NO RESPONSE A LAS LLAMADAS',
                'fecha_hora_cancelacion' => '',
                'causa_cancelacion' => '',
                'es_dependiente' => false,
                'subtarea_dependiente' => null,
                'es_ventana' => false,
                'hora_inicio_ventana' => null,
                'hora_fin_ventana' => null,
                'descripcion_completa' => '',
                'tecnicos_grupo_principal' => '[1, 2, 3]',
                'tecnicos_otros_grupos' => '[4, 5]',
                'estado' => 'EN EJECUCION',
                'cantidad_dias' => 2,
                'tipo_trabajo_id' => 1,
                'tarea_id' => 2,
                'grupo_id' => 2,
            ]
        ]);
    }
}
