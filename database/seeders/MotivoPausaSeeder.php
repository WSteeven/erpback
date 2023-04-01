<?php

namespace Database\Seeders;

use App\Models\MotivoPausa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MotivoPausaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MotivoPausa::insert([
            ['motivo' => 'DESAYUNO'],
            ['motivo' => 'ALMUERZO'],
            ['motivo' => 'MERIENDA'],
            ['motivo' => 'LOUNCH LIGHT'],
            ['motivo' => 'REFRIGERIO'],
            ['Petición de coordinación otra tarea'],
            ['Cierre de vía '],
            ['Fin de jornada '],
            ['Accidentes'],
            ['Enfermedades'],
            ['Picadura/Mordedura de insectos/animales'],
            ['Manifestaciones '],
            ['Lugar peligroso '],
            ['Asalto '],
            ['Falta de materiales '],
            ['Falta de postes '],
            ['Sin acceso a Nodo'],
            ['Espera llegada cliente '],
            ['Falta de permisos uso ductos '],
            ['Falta de permisos uso postes '],
            ['Daño de herramientas '],
            ['Daño de vehículo'],
        ]);
    }
}
