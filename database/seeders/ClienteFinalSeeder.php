<?php

namespace Database\Seeders;

use App\Models\ClienteFinal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClienteFinalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ClienteFinal::insert([
            [
                'id_cliente' => '7845DF',
                'nombres' => 'ISRAEL',
                'apellidos' => 'SARANGO',
                'celular' => '0/897564321',
                'parroquia' => 'JAMBELÍ',
                'direccion' => 'JUNIN Y OLMEDO',
                'referencias' => 'REFERENCIAS ISRAEL SARANGO',
                'coordenadas' => 'x, y fgfdgfd1',
                'provincia_id' => 1,
                'canton_id' => 2,
                'cliente_id' => 1,
            ],
            [
                'id_cliente' => '4145DF',
                'nombres' => 'CARLOS',
                'apellidos' => 'JIMENEZ',
                'celular' => '0874564321',
                'parroquia' => 'JAMBELÍ 2',
                'direccion' => '    JUNIN Y OLMEDO 2',
                'referencias' => 'REFERENCIA CARLOS JIMENEZ',
                'coordenadas' => 'x, y dsfdsfds 2',
                'provincia_id' => 2,
                'canton_id' => 3,
                'cliente_id' => 2,
            ]
        ]);
    }
}
