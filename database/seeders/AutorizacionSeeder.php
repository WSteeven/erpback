<?php

namespace Database\Seeders;

use App\Models\Autorizacion;
use App\Models\EstadosTransaccion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AutorizacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        * Estados de autorizaciones
        */
        Autorizacion::create(['nombre'=>'APROBADO']);
        Autorizacion::create(['nombre'=> 'CANCELADO']);
        Autorizacion::create(['nombre'=>'PENDIENTE']);

        /*
        Estados de transacciones
        */
        EstadosTransaccion::create(['nombre'=>'COMPLETA']);
        EstadosTransaccion::create(['nombre'=>'PARCIAL']);
        EstadosTransaccion::create(['nombre'=>'PENDIENTE']);

    }
}
