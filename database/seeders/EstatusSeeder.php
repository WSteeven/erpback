<?php

namespace Database\Seeders;

use App\Models\FondosRotativos\Usuario\Estatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Estatus::create(['descripcion'=>'ACTIVO','transcriptor'=>'ADMINISTRADOR','fecha_trans'=> date('Y-m-d')]);
        Estatus::create(['descripcion'=>'INACTIVO','transcriptor'=>'ADMINISTRADOR','fecha_trans'=> date('Y-m-d')]);
    }
}
