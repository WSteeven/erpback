<?php

namespace Database\Seeders;

use App\Models\Ventas\Comisiones;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComisionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Comisiones::insert([
            ['plan_id' => 1,'forma_pago'=>'EFECTIVO','comision'=>170],
            ['plan_id' => 2,'forma_pago'=>'EFECTIVO','comision'=>190],
            ['plan_id' => 3,'forma_pago'=>'EFECTIVO','comision'=>200],
            ['plan_id' => 1,'forma_pago'=>'D. BANCARIO','comision'=>190],
            ['plan_id' => 2,'forma_pago'=>'D. BANCARIO','comision'=>200],
            ['plan_id' => 3,'forma_pago'=>'D. BANCARIO','comision'=>220],
            ['plan_id' => 1,'forma_pago'=>'TD','comision'=>230],
            ['plan_id' => 2,'forma_pago'=>'TD','comision'=>240],
            ['plan_id' => 3,'forma_pago'=>'TD','comision'=>250],

        ]);
    }
}
