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
        TipoSaldo::insert([
            ['descripcion' => 'TRANSFERENCIA',  'id_estatus' => 1],
            ['descripcion' => 'EFECTIVO',  'id_estatus' => 1]
        ]);
    }
}
