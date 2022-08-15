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
            'codigo_barras'=> '94632820560',//strval(mt_rand()),
            'nombre_id'=>1,
            'descripcion'=>'INTEL i5/RAM 8GB/1TB/DISPLAY14.0"',
            'modelo_id'=>2,
            'serial'=>'PF369N90',
            'categoria_id'=>4,
        ]);
        Producto::create([
            'codigo_barras'=>'95122001674',// strval(mt_rand()),
            'nombre_id'=>1,
            'descripcion'=>'WINDOWS10/RAM4GB/RYZEN 3 X64/14.0"',
            'modelo_id'=>3,
            'serial'=>'5CG12204P1',
            'categoria_id'=>4,
        ]);
        Producto::create([
            'codigo_barras'=> '95122001674',
            'nombre_id'=>1,
            'descripcion'=>'INTEL i5/RAM 8GB/1TB/DISPLAY14.0',
            'modelo_id'=>3,
            'serial'=>'5CG122047B',
            'categoria_id'=>4,
        ]);
        Producto::create([
            'codigo_barras'=> '806092258808',//strval(mt_rand()),
            'nombre_id'=>3,
            'descripcion'=>'IMEI359377785932079/ANDOI 11/8.7"',
            'modelo_id'=>4,
            'serial'=>'R9JR90ES0QV',
            'categoria_id'=>4,
        ]);
        Producto::create([
            'codigo_barras'=> '806092258808',//strval(mt_rand()),
            'nombre_id'=>3,
            'descripcion'=>'IMEI359638220604445/ANDOI 11/8.7"',
            'modelo_id'=>4,
            'serial'=>'R9JRB0CQ16K',
            'categoria_id'=>4,
        ]);
        Producto::create([
            'codigo_barras'=> 'DR5K00143M',//strval(mt_rand()),
            'nombre_id'=>4,
            'descripcion'=>'HARDWARE FH0-A8B-G161222R/SOFTWARE 2.0.1(BUILD2686)SISTEMS170526E-NR/CARGADOR SLP202003020850',
            'modelo_id'=>5,
            'serial'=>'E5FHA05805',
            'categoria_id'=>5,
        ]);
        Producto::create([
            'codigo_barras'=> 'DR5K00143M',//strval(mt_rand()),
            'nombre_id'=>4,
            'descripcion'=>'HARDWARE FH0-A8B-G161222R/SOFTWARE 2.0.1(BUILD2686)SISTEMS170526E-NR/CARGADOR SLP201912100019',
            'modelo_id'=>5,
            'serial'=>'E5FHA05026',
            'categoria_id'=>5,
        ]);
        Producto::create([
            'nombre_id'=>5,
            'descripcion'=>'SC/UPC-SC/UPC-SM-3.0MM-1KM',
            'modelo_id'=>9,
            'serial'=>'2111020005',
            'categoria_id'=>5,
        ]);
        Producto::create([
            'nombre_id'=>6,
            'descripcion'=>'INPUT:100-240V AC 1.0A/OUTPUT:DC10V/SIN CABLE DE PODER',
            'modelo_id'=>6,
            'serial'=>'SLP202105270474',
            'categoria_id'=>5,
        ]);
        Producto::create([
            'nombre_id'=>13,
            'descripcion'=>'FIBER CLEAVER GW-800/F2H/GW-800/GW1000021 Nº 1',
            'modelo_id'=>7,
            'serial'=>'GW1000021',
            'categoria_id'=>5,
        ]);
        Producto::create([
            'nombre_id'=>14,
            'descripcion'=>'VISUAL FOULT LOCATOR/F2H/VLS-8-01/GJ01011131 Nº 1',
            'modelo_id'=>8,
            'serial'=>'GJ01011131',
            'categoria_id'=>5,
        ]);
        Producto::create([
            'nombre_id'=>15,
            'descripcion'=>'OPTICAL POWER METER/F2H/FHP12-A/GZ12A35446 Nº 1',
            'modelo_id'=>9,
            'serial'=>'GZ12A35446',
            'categoria_id'=>5,
        ]);
        Producto::create([
            'nombre_id'=>16,
            'descripcion'=>'500W/LAPTOP 19"/TB/BATTERI CHARGERS',
            'modelo_id'=>10,
            'serial'=>'DD71201903273469506',
            'categoria_id'=>5,
        ]);
        Producto::create([
            'nombre_id'=>17,
            'descripcion'=>'CLEANER PEN SC/F2H/STANDARD',
            'modelo_id'=>23,
            'serial'=>'DD71201903273469506',
            'categoria_id'=>5,
        ]);
        Producto::create([
            'nombre_id'=>18,
            'descripcion'=>'ROUTER 2 ANTENAS/TENDA/3805E8',
            'modelo_id'=>24,
            'categoria_id'=>5,
        ]);

        //fibras 42,43,44
        Producto::create([
            'nombre_id'=>42,
            'descripcion'=>'ADSS CABLE SPAN 120M 48 FO G652D',
            'modelo_id'=>14,
            'categoria_id'=>6,
            'hilo_id'=>1,
            'serial'=>'C180372'
        ]);
        Producto::create([
            'nombre_id'=>43,
            'descripcion'=>'ADSS CABLE SPAN 250M 24 FO B1.3',
            'modelo_id'=>14,
            'categoria_id'=>6,
            'hilo_id'=>1,
            'serial'=>'DB21110683'
        ]);
        Producto::create([
            'nombre_id'=>44,
            'descripcion'=>'FIBRA DROP BOW TIE SHAPE 2 FO G657A2 METRAJE (PRECONECTARIZADO 150M)',
            'modelo_id'=>14,
            'categoria_id'=>6,
            'hilo_id'=>2,
            'serial'=>'DB210041123-150-001',
            'punta_b'=>150
        ]);
        Producto::create([
            'nombre_id'=>44,
            'descripcion'=>'FIBRA DROP BOW TIE SHAPE 2 FO G657A2 METRAJE (PRECONECTARIZADO 200M)',
            'modelo_id'=>14,
            'categoria_id'=>6,
            'hilo_id'=>2,
            'serial'=>'DA21031044-200-001',
            'punta_b'=>200
        ]);
        Producto::create([
            'nombre_id'=>44,
            'descripcion'=>'FIBRA DROP BOW TIE SHAPE 2 FO G657A2 METRAJE (PRECONECTARIZADO 250M)',
            'modelo_id'=>14,
            'categoria_id'=>6,
            'hilo_id'=>2,
            'serial'=>'DB21031113-250-001',
            'punta_b'=>250
        ]);

    }
}
