<?php

namespace Database\Seeders;

use App\Models\Medico\Respuesta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RespuestaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Respuesta::insert([
            [
                'respuesta' => 'Muy clara',
                'valor' => 1,
            ],
            [
                'respuesta' => 'Clara',
                'valor' => 2,
            ],
            [
                'respuesta' => 'Poco clara',
                'valor' => 3,
            ],
            [
                'respuesta' => 'Nada clara',
                'valor' => 4,
            ],
            [
                'respuesta' => 'Siempre o casi siempre',
                'valor' => 1,
            ],
            [
                'respuesta' => 'A menudo',
                'valor' => 2,
            ],
            [
                'respuesta' => 'A veces',
                'valor' => 3,
            ],
            [
                'respuesta' => 'Nunca o casi nunca',
                'valor' => 4,
            ],
            [
                'respuesta' => 'No tengo, no hay otras personas',
                'valor' => 5,
            ],
            [
                'respuesta' => 'No trabajo turnos rotativos',
                'valor' => 5,
            ],
            [
                'respuesta' => 'No hay información',
                'valor' => 1,
            ],
            [
                'respuesta' => 'Insuficiente',
                'valor' => 2,
            ],
            [
                'respuesta' => 'Es adecuada',
                'valor' => 3,
            ],
            [
                'respuesta' => 'Puede decidir',
                'valor' => 1,
            ],
            [
                'respuesta' => 'Se me consulta',
                'valor' => 2,
            ],
            [
                'respuesta' => 'Solo recibo informacion',
                'valor' => 3,
            ],
            [
                'respuesta' => 'Ninguna participacion',
                'valor' => 4,
            ],
        ]);
    }
}
