<?php

namespace Database\Seeders\ControlPersonal;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HorarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\ControlPersonal\HorarioSeeder"
     *
     * @return void
     */
    public function run()
    {
        DB::table('rrhh_cp_horario_laboral')->insert([
            [
                'id' => 1,
                'nombre' => 'NORMAL',
                'dia' => 'LUNES',
                'hora_entrada' => '08:00:00',
                'hora_salida' => '17:00:00',
                'inicio_pausa' => '12:30:00',
                'fin_pausa' => '13:30:00',
                'activo' => 1,
                'created_at' => '2025-01-15 09:48:32',
                'updated_at' => '2025-01-15 10:07:55',
            ],
            [
                'id' => 2,
                'nombre' => 'NORMAL',
                'dia' => 'MARTES',
                'hora_entrada' => '08:00:00',
                'hora_salida' => '17:00:00',
                'inicio_pausa' => '12:30:00',
                'fin_pausa' => '13:30:00',
                'activo' => 1,
                'created_at' => '2025-01-15 10:09:12',
                'updated_at' => '2025-01-15 10:09:12',
            ],
            [
                'id' => 3,
                'nombre' => 'NORMAL',
                'dia' => 'MIERCOLES',
                'hora_entrada' => '08:00:00',
                'hora_salida' => '17:00:00',
                'inicio_pausa' => '12:30:00',
                'fin_pausa' => '13:30:00',
                'activo' => 1,
                'created_at' => '2025-01-15 10:09:39',
                'updated_at' => '2025-01-15 10:11:43',
            ],
            [
                'id' => 4,
                'nombre' => 'NORMAL',
                'dia' => 'JUEVES',
                'hora_entrada' => '08:00:00',
                'hora_salida' => '17:00:00',
                'inicio_pausa' => '12:30:00',
                'fin_pausa' => '13:30:00',
                'activo' => 1,
                'created_at' => '2025-01-15 10:10:03',
                'updated_at' => '2025-01-15 10:10:03',
            ],
            [
                'id' => 5,
                'nombre' => 'NORMAL',
                'dia' => 'VIERNES',
                'hora_entrada' => '08:00:00',
                'hora_salida' => '17:00:00',
                'inicio_pausa' => '12:30:00',
                'fin_pausa' => '13:30:00',
                'activo' => 1,
                'created_at' => '2025-01-15 10:10:31',
                'updated_at' => '2025-01-15 10:10:31',
            ],
        ]);
    }
}
