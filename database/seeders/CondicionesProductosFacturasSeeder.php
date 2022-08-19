<?php

namespace Database\Seeders;

use App\Models\Condicion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CondicionesProductosFacturasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Condicion::create(['nombre'=>'NUEVO']);
        Condicion::create(['nombre'=>'USADO']);
        Condicion::create(['nombre'=>'MAL ESTADO']);
        Condicion::create(['nombre'=>'DAÑADO']);
    }
}
