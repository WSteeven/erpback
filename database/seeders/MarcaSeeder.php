<?php

namespace Database\Seeders;

use App\Models\Marca;
use App\Models\Modelo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Marcas
         */
        Marca::create(['nombre'=>'SIN MARCA']);
        Marca::create(['nombre'=>'DELL']);
        Marca::create(['nombre'=>'LENOVO']);
        Marca::create(['nombre'=>'HP']);
        Marca::create(['nombre'=>'EPSON']);
        Marca::create(['nombre'=>'SAMSUNG']);
        Marca::create(['nombre'=>'F2H']);
        Marca::create(['nombre'=>'BELT']);
        Marca::create(['nombre'=>'TENDA']);
        Marca::create(['nombre'=>'STYLER']);
        Marca::create(['nombre'=>'SIGNAL FIRE']);
        Marca::create(['nombre'=>'FLUKE']);
        Marca::create(['nombre'=>'SUNKIT']);
        Marca::create(['nombre'=>'CTC']);
        Marca::create(['nombre'=>'TACTIX']);

        /**
         * Modelos
         */
        Modelo::create([
            'nombre'=>'SIN MODELO',
            'marca_id'=>1
        ]);
        Modelo::create([
            'nombre'=>'8264',
            'marca_id'=>3
        ]);
        Modelo::create([
            'nombre'=>'HP245G7',
            'marca_id'=>4
        ]);
        Modelo::create([
            'nombre'=>'A7 LITE',
            'marca_id'=>6
        ]);
        Modelo::create([
            'nombre'=>'FH05000-D35-A',
            'marca_id'=>7
        ]);
        Modelo::create([
            'nombre'=>'MH-100400',
            'marca_id'=>7
        ]);
        Modelo::create([
            'nombre'=>'GW-800',
            'marca_id'=>7
        ]);
        Modelo::create([
            'nombre'=>'VLS-8-01',
            'marca_id'=>7
        ]);
        Modelo::create([
            'nombre'=>'EBLPI400',
            'marca_id'=>8
        ]);
        Modelo::create([
            'nombre'=>'HT-10',
            'marca_id'=>7
        ]);
        Modelo::create([
            'nombre'=>'SC/LC',
            'marca_id'=>1
        ]);
    }
}
