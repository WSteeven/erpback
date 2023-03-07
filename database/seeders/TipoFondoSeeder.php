<?php

namespace Database\Seeders;

use App\Models\FondosRotativos\Gasto\TipoFondo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoFondoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoFondo::create(['descripcion'=>'INDIVIDUAL','transcriptor'=>'ADMINISTRADOR','fecha_trans'=>date('Y-m-d')]);
        TipoFondo::create(['descripcion'=>'GRUPAL','transcriptor'=>'ADMINISTRADOR','fecha_trans'=>date('Y-m-d')]);
    }
}
