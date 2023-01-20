<?php

namespace Database\Seeders;

use App\Models\Autorizacion;
use App\Models\EstadoTransaccion;
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
        Autorizacion::create(['nombre'=>'PENDIENTE']);
        Autorizacion::create(['nombre'=>'APROBADO']);
        Autorizacion::create(['nombre'=> 'CANCELADO']);

        /*
        Estados de transacciones
        */
        EstadoTransaccion::create(['nombre'=>'PENDIENTE']);
        EstadoTransaccion::create(['nombre'=>'COMPLETA']);
        EstadoTransaccion::create(['nombre'=>'PARCIAL']);
        EstadoTransaccion::create(['nombre'=>'NO REALIZADA']);

    }
}
