<?php

namespace Database\Seeders;

use App\Models\TipoTrabajo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoTareaSeeder extends Seeder
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
                'nombre' => 'TENDIDO',
                'cliente_id' => 2
            ],
            [
                'nombre' => 'DESMONTAJE',
                'cliente_id' => 2
            ],
            [
                'nombre' => 'HINCADO',
                'cliente_id' => 2
            ],
            [
                'nombre' => 'RETIRO',
                'cliente_id' => 2
            ],
            [
                'nombre' => 'DESMONTAJE (M)',
                'cliente_id' => 3
            ],
            [
                'nombre' => 'INSTALACION',
                'cliente_id' => 3
            ],
            [
                'nombre' => 'CABLEADO',
                'cliente_id' => 3
            ],
            [
                'nombre' => 'ASISTENCIA NODO NEDETEL',
                'cliente_id' => 3
            ],
            [
                'nombre' => 'ASISTENCIA NODO CLIENTE',
                'cliente_id' => 3
            ],
            [
                'nombre' => 'MIGRACIÓN',
                'cliente_id' => 3
            ],
            [
                'nombre' => 'EMERGENCIA',
                'cliente_id' => 3
            ],
            [
                'nombre' => 'RECORRIDO',
                'cliente_id' => 3
            ],
            [
                'nombre' => 'FACTIBILIDAD',
                'cliente_id' => 3
            ],
            [
                'nombre' => 'AUDITORÍA',
                'cliente_id' => 3
            ],
            [
                'nombre' => 'LEVANTAMIENTO DE INFORMACIÓN',
                'cliente_id' => 3
            ],
            [
                'nombre' => 'LOGÍSTICA',
                'cliente_id' => 3
            ],
            [
                'nombre' => 'DESINSTALACIÓN',
                'cliente_id' => 3
            ],
            [
                'nombre' => 'RETIRO DE EQUIPOS',
                'cliente_id' => 3
            ],
            [
                'nombre' => 'DESMONTAJE',
                'cliente_id' => 3
            ],
            [
                'nombre' => 'CERTIFICACIÓN',
                'cliente_id' => 3
            ],
            [
                'nombre' => 'ENVÍO DE INFORMACIÓN',
                'cliente_id' => 3
            ],
            [
                'nombre' => 'TRASLADO',
                'cliente_id' => 3
            ],
            [
                'nombre' => 'ALIMENTACIÓN',
                'cliente_id' => 1,
            ],
            [
                'nombre' => 'ALIMENTACIÓN',
                'cliente_id' => 2,
            ],
            [
                'nombre' => 'ALIMENTACIÓN',
                'cliente_id' => 3,
            ],
            [
                'nombre' => 'ALIMENTACIÓN',
                'cliente_id' => 4,
            ],
        ]);
    }
}
