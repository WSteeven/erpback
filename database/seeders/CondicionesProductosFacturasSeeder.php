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
        Condicion::create(['condicion'=>'NUEVO']);
        Condicion::create(['condicion'=>'USADO']);
        Condicion::create(['condicion'=>'MAL ESTADO']);
        Condicion::create(['condicion'=>'DAÑADO']);
    }
}
