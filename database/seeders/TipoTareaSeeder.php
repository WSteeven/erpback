<?php

namespace Database\Seeders;

use App\Models\TipoTarea;
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
        TipoTarea::insert([
            [
                'nombre' => 'TENDIDO',
                'cliente_id' => 1
            ],
            [
                'nombre' => 'DESMONTAJE',
                'cliente_id' => 1
            ],
            [
                'nombre' => 'HINCADO',
                'cliente_id' => 1
            ],
            [
                'nombre' => 'RETIRO',
                'cliente_id' => 1
            ],
            [
                'nombre' => 'DESMONTAJE (M)',
                'cliente_id' => 2
            ],
            [
                'nombre' => 'INSTALACION',
                'cliente_id' => 2
            ],
            [
                'nombre' => 'CABLEADO',
                'cliente_id' => 2
            ],
            [
                'nombre' => 'ASISTENCIA NODO NEDETEL',
                'cliente_id' => 2
            ],
            [
                'nombre' => 'ASISTENCIA NODO CLIENTE',
                'cliente_id' => 2
            ],
            [
                'nombre' => 'MIGRACIÓN',
                'cliente_id' => 2
            ],
            [
                'nombre' => 'EMERGENCIA',
                'cliente_id' => 2
            ],
            [
                'nombre' => 'RECORRIDO',
                'cliente_id' => 2
            ],
            [
                'nombre' => 'FACTIBILIDAD',
                'cliente_id' => 2
            ],
            [
                'nombre' => 'AUDITORÍA',
                'cliente_id' => 2
            ],
            [
                'nombre' => 'LEVANTAMIENTO DE INFORMACIÓN',
                'cliente_id' => 2
            ],
            [
                'nombre' => 'LOGÍSTICA',
                'cliente_id' => 2
            ],
            [
                'nombre' => 'DESINSTALACIÓN',
                'cliente_id' => 2
            ],
            [
                'nombre' => 'RETIRO DE EQUIPOS',
                'cliente_id' => 2
            ],
            [
                'nombre' => 'DESMONTAJE',
                'cliente_id' => 2
            ],
            [
                'nombre' => 'CERTIFICACIÓN',
                'cliente_id' => 2
            ],
            [
                'nombre' => 'ENVÍO DE INFORMACIÓN',
                'cliente_id' => 2
            ],
            [
                'nombre' => 'TRASLADO',
                'cliente_id' => 2
            ],
        ]);
    }
}
