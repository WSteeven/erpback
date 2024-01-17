<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class ProductosClaroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fechaActual = Date::now()->format('Y-m-d');
        $datos = [
            ['100086831',24.00,2,$fechaActual,$fechaActual],
            ['100086833',29.00,2,$fechaActual,$fechaActual],
            ['100086815',29.00,2,$fechaActual,$fechaActual],
            ['100086849',34.00,2,$fechaActual,$fechaActual],
            ['100087929',30.00,3,$fechaActual,$fechaActual],
            ['100088204',35.00,3,$fechaActual,$fechaActual],
            ['100088250',43.00,3,$fechaActual,$fechaActual],
            ['100086817',29.00,2,$fechaActual,$fechaActual],
            ['100086854',34.00,2,$fechaActual,$fechaActual],
            ['100087168',30.00,3,$fechaActual,$fechaActual],
            ['100088032',35.00,3,$fechaActual,$fechaActual],
            ['100088248',43.00,3,$fechaActual,$fechaActual],
            ['100086797',24.00,2,$fechaActual,$fechaActual],
            ['100086800',29.00,2,$fechaActual,$fechaActual],
            ['100086839',29.00,2,$fechaActual,$fechaActual],
            ['100086804',34.00,2,$fechaActual,$fechaActual],
            ['100086911',30.00,3,$fechaActual,$fechaActual],
            ['100088270',35.00,3,$fechaActual,$fechaActual],
            ['100086801',43.00,3,$fechaActual,$fechaActual],
            ['100086884',29.00,2,$fechaActual,$fechaActual],
            ['100086882',34.00,2,$fechaActual,$fechaActual],
            ['100088266',30.00,3,$fechaActual,$fechaActual],
            ['100088268',35.00,3,$fechaActual,$fechaActual],
            ['100086809',43.00,3,$fechaActual,$fechaActual],
            ['100087587',19.00,2,$fechaActual,$fechaActual],
            ['100087722',21.00,2,$fechaActual,$fechaActual],
            ['100087724',24.00,2,$fechaActual,$fechaActual],
            ['100087357',26.00,2,$fechaActual,$fechaActual],
            ['100087726',25.00,3,$fechaActual,$fechaActual],
            ['100087772',29.00,3,$fechaActual,$fechaActual],
            ['100087814',35.00,3,$fechaActual,$fechaActual],
            ['100087512',24.00,2,$fechaActual,$fechaActual],
            ['100087353',26.00,2,$fechaActual,$fechaActual],
            ['100087711',25.00,3,$fechaActual,$fechaActual],
            ['100087817',29.00,3,$fechaActual,$fechaActual],
            ['100087819',35.00,3,$fechaActual,$fechaActual],
            ['100087262',19.00,2,$fechaActual,$fechaActual],
            ['100087343',21.00,2,$fechaActual,$fechaActual],
            ['100087346',24.00,2,$fechaActual,$fechaActual],
            ['100087518',26.00,2,$fechaActual,$fechaActual],
            ['100087971',25.00,3,$fechaActual,$fechaActual],
            ['100087974',29.00,3,$fechaActual,$fechaActual],
            ['100087831',35.00,3,$fechaActual,$fechaActual],
            ['100087376',24.00,2,$fechaActual,$fechaActual],
            ['100087515',26.00,2,$fechaActual,$fechaActual],
            ['100087990',25.00,3,$fechaActual,$fechaActual],
            ['100087940',29.00,3,$fechaActual,$fechaActual],
            ['100087702',35.00,3,$fechaActual,$fechaActual],
            ['100087646',19.00,2,$fechaActual,$fechaActual],
            ['100087638',21.00,2,$fechaActual,$fechaActual],
            ['100087681',24.00,2,$fechaActual,$fechaActual],
            ['100087580',26.00,2,$fechaActual,$fechaActual],
            ['100087696',25.00,3,$fechaActual,$fechaActual],
            ['100087797',29.00,3,$fechaActual,$fechaActual],
            ['100087596',35.00,3,$fechaActual,$fechaActual],
            ['100087682',24.00,2,$fechaActual,$fechaActual],
            ['100087577',26.00,2,$fechaActual,$fechaActual],
            ['100087674',25.00,3,$fechaActual,$fechaActual],
            ['100087951',29.00,3,$fechaActual,$fechaActual],
            ['100087776',35.00,3,$fechaActual,$fechaActual],
            ['100087629',19.00,2,$fechaActual,$fechaActual],
            ['100087549',21.00,2,$fechaActual,$fechaActual],
            ['100087555',24.00,2,$fechaActual,$fechaActual],
            ['100087497',26.00,2,$fechaActual,$fechaActual],
            ['100087698',25.00,3,$fechaActual,$fechaActual],
            ['100088025',29.00,3,$fechaActual,$fechaActual],
            ['100087847',35.00,3,$fechaActual,$fechaActual],
            ['100087557',24.00,2,$fechaActual,$fechaActual],
            ['100087499',26.00,2,$fechaActual,$fechaActual],
            ['100087829',25.00,3,$fechaActual,$fechaActual],
            ['100088026',29.00,3,$fechaActual,$fechaActual],
            ['100087848',35.00,3,$fechaActual,$fechaActual],
            ['100086600',20.00,1,$fechaActual,$fechaActual],
            ['100086647',22.00,1,$fechaActual,$fechaActual],
            ['100086648',25.00,1,$fechaActual,$fechaActual],
            ['100086649',35.00,1,$fechaActual,$fechaActual],
            ['100086613',70.00,1,$fechaActual,$fechaActual],
            ['100086620',135.00,1,$fechaActual,$fechaActual],
            ['100086532',20.00,1,$fechaActual,$fechaActual],
            ['100086533',22.00,1,$fechaActual,$fechaActual],
            ['100086534',25.00,1,$fechaActual,$fechaActual],
            ['100086535',35.00,1,$fechaActual,$fechaActual],
            ['100086529',70.00,1,$fechaActual,$fechaActual],
            ['100087218',15.00,1,$fechaActual,$fechaActual],
            ['100087233',16.00,1,$fechaActual,$fechaActual],
            ['100087234',17.00,1,$fechaActual,$fechaActual],
            ['100087250',25.00,1,$fechaActual,$fechaActual],
            ['100087252',50.00,1,$fechaActual,$fechaActual],
            ['100087268',100.00,1,$fechaActual,$fechaActual],
            ['100087224',15.00,1,$fechaActual,$fechaActual],
            ['100087226',16.00,1,$fechaActual,$fechaActual],
            ['100087265',17.00,1,$fechaActual,$fechaActual],
            ['100087269',25.00,1,$fechaActual,$fechaActual],
            ['100087272',50.00,1,$fechaActual,$fechaActual],
            ['100086541',15.00,1,$fechaActual,$fechaActual],
            ['100086543',16.00,1,$fechaActual,$fechaActual],
            ['100086563',17.00,1,$fechaActual,$fechaActual],
            ['100086565',25.00,1,$fechaActual,$fechaActual],
            ['100086566',50.00,1,$fechaActual,$fechaActual],
            ['100086562',100.00,1,$fechaActual,$fechaActual],
            ['100086593',15.00,1,$fechaActual,$fechaActual],
            ['100086595',16.00,1,$fechaActual,$fechaActual],
            ['100086597',17.00,1,$fechaActual,$fechaActual],
            ['100086642',25.00,1,$fechaActual,$fechaActual],
            ['100086644',50.00,1,$fechaActual,$fechaActual],

    ];
    foreach ($datos as $fila) {
        DB::insert('INSERT INTO `ventas_productos_ventas` (`bundle_id`, `precio`,  `plan_id`, `created_at`,  `updated_at`) VALUES(?,?,?,?,?)', $fila);
    }
    }
}
