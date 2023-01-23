<?php

namespace Database\Seeders;

use App\Models\TipoTrabajo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoTrabajoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoTrabajo::insert([
            [
                'descripcion' => 'TENDIDO',
                'cliente_id' => 2
            ],
            [
                'descripcion' => 'DESMONTAJE',
                'cliente_id' => 2
            ],
            [
                'descripcion' => 'HINCADO',
                'cliente_id' => 2
            ],
            [
                'descripcion' => 'RETIRO',
                'cliente_id' => 2
            ],
            [
                'descripcion' => 'DESMONTAJE (M)',
                'cliente_id' => 3
            ],
            [
                'descripcion' => 'INSTALACION',
                'cliente_id' => 3
            ],
            [
                'descripcion' => 'CABLEADO',
                'cliente_id' => 3
            ],
            [
                'descripcion' => 'ASISTENCIA NODO NEDETEL',
                'cliente_id' => 3
            ],
            [
                'descripcion' => 'ASISTENCIA NODO CLIENTE',
                'cliente_id' => 3
            ],
            [
                'descripcion' => 'MIGRACIÓN',
                'cliente_id' => 3
            ],
            [
                'descripcion' => 'EMERGENCIA',
                'cliente_id' => 3
            ],
            [
                'descripcion' => 'RECORRIDO',
                'cliente_id' => 3
            ],
            [
                'descripcion' => 'FACTIBILIDAD',
                'cliente_id' => 3
            ],
            [
                'descripcion' => 'AUDITORÍA',
                'cliente_id' => 3
            ],
            [
                'descripcion' => 'LEVANTAMIENTO DE INFORMACIÓN',
                'cliente_id' => 3
            ],
            [
                'descripcion' => 'LOGÍSTICA',
                'cliente_id' => 3
            ],
            [
                'descripcion' => 'DESINSTALACIÓN',
                'cliente_id' => 3
            ],
            [
                'descripcion' => 'RETIRO DE EQUIPOS',
                'cliente_id' => 3
            ],
            [
                'descripcion' => 'DESMONTAJE',
                'cliente_id' => 3
            ],
            [
                'descripcion' => 'CERTIFICACIÓN',
                'cliente_id' => 3
            ],
            [
                'descripcion' => 'ENVÍO DE INFORMACIÓN',
                'cliente_id' => 3
            ],
            [
                'descripcion' => 'TRASLADO',
                'cliente_id' => 3
            ],
            [
                'descripcion' => 'ALIMENTACIÓN',
                'cliente_id' => 1,
            ],
            [
                'descripcion' => 'ALIMENTACIÓN',
                'cliente_id' => 2,
            ],
            [
                'descripcion' => 'ALIMENTACIÓN',
                'cliente_id' => 3,
            ],
            [
                'descripcion' => 'ALIMENTACIÓN',
                'cliente_id' => 4,
            ],
        ]);
    }
}
