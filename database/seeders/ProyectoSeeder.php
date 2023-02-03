<?php

namespace Database\Seeders;

use App\Models\Proyecto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProyectoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Proyecto::insert([
            [
                'codigo_proyecto' => 'UFC0622FTTH',
                'nombre' => 'CONSTRUCCIÓN FTTH CUENCA 1',
                'nodo_interconexion' => 'CUE01-1SEG01',
                'fecha_inicio' => '01-12-2022',
                'fecha_fin' => '31-12-2022',
                // 'costo' => '2000',
                'canton_id' => 1,
                'cliente_id' => 3,
                'coordinador_id' => 3,
                'finalizado' => false
            ],
            [
                'codigo_proyecto' => 'UFC0548FTTH',
                'nombre' => 'RELEVAMIENTO DE INFORMACIÓN CAYAMBE',
                'nodo_interconexion' => null,
                'fecha_inicio' => '01-12-2022',
                'fecha_fin' => '10-01-2023',
                // 'costo' => '2000',
                'canton_id' => 1,
                'cliente_id' => 3,
                'coordinador_id' => 3,
                'finalizado' => true
            ]
        ]);
    }
}
