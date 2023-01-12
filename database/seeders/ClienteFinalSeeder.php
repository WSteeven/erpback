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
                'id_cliente_final' => '7845DF',
                'nombres' => 'ISRAEL',
                'apellidos' => 'SARANGO',
                'celular' => '0/897564321',
                'parroquia' => 'JAMBELÍ',
                'direccion' => 'JUNIN Y OLMEDO',
                'referencia' => 'REFERENCIAS ISRAEL SARANGO',
                'coordenada_latitud' => '-79,4512',
                'coordenada_longitud' => '-35,8957',
                'provincia_id' => 1,
                'canton_id' => 2,
                'cliente_id' => 1,
            ],
            [
                'id_cliente_final' => '4145DF',
                'nombres' => 'CARLOS',
                'apellidos' => 'JIMENEZ',
                'celular' => '0874564321',
                'parroquia' => 'JAMBELÍ 2',
                'direccion' => '    JUNIN Y OLMEDO 2',
                'referencia' => 'REFERENCIA CARLOS JIMENEZ',
                'coordenada_latitud' => '-79,4512',
                'coordenada_longitud' => '-35,8957',
                'provincia_id' => 2,
                'canton_id' => 3,
                'cliente_id' => 2,
            ],
            [
                'id_cliente_final' => 'JGFU876',
                'nombres' => 'EVELYN',
                'apellidos' => 'DUARTE',
                'celular' => '0874564874',
                'parroquia' => 'JAMBELÍ 45',
                'direccion' => 'CALLE ARIZAGA',
                'referencia' => 'REFERENCIA A TIENDA JAIMITO',
                'coordenada_latitud' => '-79,4532',
                'coordenada_longitud' => '-15,8957',
                'provincia_id' => 2,
                'canton_id' => 3,
                'cliente_id' => 3,
            ]
        ]);
    }
}
