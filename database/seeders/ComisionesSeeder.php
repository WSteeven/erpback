<?php

namespace Database\Seeders;

use App\Models\Ventas\Comision;
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
        Comision::insert([
            ['plan_id' => 1,'forma_pago'=>'EFECTIVO','comision'=>170,'tipo_vendedor'=>'VENDEDOR'],
            ['plan_id' => 2,'forma_pago'=>'EFECTIVO','comision'=>190,'tipo_vendedor'=>'VENDEDOR'],
            ['plan_id' => 3,'forma_pago'=>'EFECTIVO','comision'=>200,'tipo_vendedor'=>'VENDEDOR'],
            ['plan_id' => 1,'forma_pago'=>'D. BANCARIO','comision'=>190,'tipo_vendedor'=>'VENDEDOR'],
            ['plan_id' => 2,'forma_pago'=>'D. BANCARIO','comision'=>200,'tipo_vendedor'=>'VENDEDOR'],
            ['plan_id' => 3,'forma_pago'=>'D. BANCARIO','comision'=>220,'tipo_vendedor'=>'VENDEDOR'],
            ['plan_id' => 1,'forma_pago'=>'TC','comision'=>230,'tipo_vendedor'=>'VENDEDOR'],
            ['plan_id' => 2,'forma_pago'=>'TC','comision'=>240,'tipo_vendedor'=>'VENDEDOR'],
            ['plan_id' => 3,'forma_pago'=>'TC','comision'=>250,'tipo_vendedor'=>'VENDEDOR'],
            //Supervisor
            ['plan_id' => 1,'forma_pago'=>'EFECTIVO','comision'=>2,'tipo_vendedor'=>'SUPERVISOR_VENTAS'],
            ['plan_id' => 2,'forma_pago'=>'EFECTIVO','comision'=>3,'tipo_vendedor'=>'SUPERVISOR_VENTAS'],
            ['plan_id' => 3,'forma_pago'=>'EFECTIVO','comision'=>4,'tipo_vendedor'=>'SUPERVISOR_VENTAS'],
            ['plan_id' => 1,'forma_pago'=>'D. BANCARIO','comision'=>3,'tipo_vendedor'=>'SUPERVISOR_VENTAS'],
            ['plan_id' => 2,'forma_pago'=>'D. BANCARIO','comision'=>4,'tipo_vendedor'=>'SUPERVISOR_VENTAS'],
            ['plan_id' => 3,'forma_pago'=>'D. BANCARIO','comision'=>5,'tipo_vendedor'=>'SUPERVISOR_VENTAS'],
            ['plan_id' => 1,'forma_pago'=>'TC','comision'=>4,'tipo_vendedor'=>'SUPERVISOR_VENTAS'],
            ['plan_id' => 2,'forma_pago'=>'TC','comision'=>5,'tipo_vendedor'=>'SUPERVISOR_VENTAS'],
            ['plan_id' => 3,'forma_pago'=>'TC','comision'=>6,'tipo_vendedor'=>'SUPERVISOR_VENTAS'],
        ]);
    }
}
