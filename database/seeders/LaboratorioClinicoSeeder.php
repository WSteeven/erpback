<?php

namespace Database\Seeders;

use App\Models\Medico\LaboratorioClinico;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LaboratorioClinicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LaboratorioClinico::insert([
            [
                'nombre' => 'LABORATORIO #1 MACHALA',
                'direccion' => 'DIRECCION INVENTADA 1',
                'celular' => '0987654321',
                'correo' => 'LABORATORIO1@GMAIL.COM',
                'coordenadas' => '',
                'activo' => true,
                'canton_id' => 53,
            ],
            [
                'nombre' => 'LABORATORIO #2 MACHALA',
                'direccion' => 'DIRECCION INVENTADA 2',
                'celular' => '0987654323',
                'correo' => 'LABORATORIO2@GMAIL.COM',
                'coordenadas' => '',
                'activo' => true,
                'canton_id' => 53,
            ],
            [
                'nombre' => 'LABORATORIO #3 MACHALA',
                'direccion' => 'DIRECCION INVENTADA 3',
                'celular' => '0987654323',
                'correo' => 'LABORATORIO3@GMAIL.COM',
                'coordenadas' => '',
                'activo' => true,
                'canton_id' => 53,
            ],
            [
                'nombre' => 'LABORATORIO #1 SANTA ROSA',
                'direccion' => 'DIRECCION INVENTADA 4 SANTA ROSA',
                'celular' => '0987654324',
                'correo' => 'LABORATORIO4@GMAIL.COM',
                'coordenadas' => '',
                'activo' => true,
                'canton_id' => 64,
            ],
        ]);
    }
}
