<?php

namespace Database\Seeders;

use App\Models\FondosRotativos\Saldo\TipoSaldo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoSaldoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoSaldo::create(['descripcion'=>'TRANSFERENCIA','transcriptor'=>'ADMINISTRADOR','id_estatus'=>1,'fecha_trans'=>date('Y-m-d')]);
        TipoSaldo::create(['descripcion'=>'TRANSFERENCIA  VALORES','transcriptor'=>'ADMINISTRADOR','id_estatus'=>1,'fecha_trans'=>date('Y-m-d')]);
        TipoSaldo::create(['descripcion'=>'CASH','transcriptor'=>'ADMINISTRADOR','id_estatus'=>1,'fecha_trans'=>date('Y-m-d')]);
        TipoSaldo::create(['descripcion'=>'OTRO','transcriptor'=>'ADMINISTRADOR','id_estatus'=>1,'fecha_trans'=>date('Y-m-d')]);

    }
}
