<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Producto::create([
            'codigo_barras'=> strval(mt_rand()),
            'nombre_id'=>1,
            'descripcion'=>'INTEL i5/RAM 8GB/1TB/DISPLAY14.0"',
            'modelo_id'=>2,
            'serial'=>strval(mt_rand()),
            'categoria_id'=>4,
        ]);
        Producto::create([
            'codigo_barras'=> strval(mt_rand()),
            'nombre_id'=>1,
            'descripcion'=>'WINDOWS10/RAM4GB/RYZEN 3 X64/14.0"',
            'modelo_id'=>3,
            'serial'=>'5CG12204P1',
            'categoria_id'=>4,
        ]);
        Producto::create([
            'codigo_barras'=> strval(mt_rand()),
            'nombre_id'=>1,
            'descripcion'=>'INTEL i5/RAM 8GB/1TB/DISPLAY14.0',
            'modelo_id'=>3,
            'serial'=>'5CG122047B',
            'categoria_id'=>4,
        ]);

    }
}
