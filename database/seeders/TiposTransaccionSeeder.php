<?php

namespace Database\Seeders;

use App\Models\SubtipoTransaccion;
use App\Models\TipoTransaccion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TiposTransaccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* INGRESOS */
        //
        TipoTransaccion::create(['nombre'=>'COMPRA A PROVEEDOR', 'tipo'=>'INGRESO']);
        SubtipoTransaccion::create(['nombre'=>'COMPRA A PROVEEDOR','tipo_transaccion_id'=>1]);
        TipoTransaccion::create(['nombre'=>'MERCADERIA DE CLIENTE', 'tipo'=>'INGRESO']);
        SubtipoTransaccion::create(['nombre'=>'ABASTECIMIENTO DE STOCK','tipo_transaccion_id'=>2]);
        SubtipoTransaccion::create(['nombre'=>'MATERIALES PARA TAREAS','tipo_transaccion_id'=>2]);
        TipoTransaccion::create(['nombre'=>'DEVOLUCION DEL PERSONAL', 'tipo'=>'INGRESO']);
        SubtipoTransaccion::create(['nombre'=>'FINALIZACION LABORAL','tipo_transaccion_id'=>3]);
        SubtipoTransaccion::create(['nombre'=>'GARANTIA','tipo_transaccion_id'=>3]);
        SubtipoTransaccion::create(['nombre'=>'REPOSICION','tipo_transaccion_id'=>3]);
        SubtipoTransaccion::create(['nombre'=>'DAÑO','tipo_transaccion_id'=>3]);
        TipoTransaccion::create(['nombre'=>'DEVOLUCION DE TAREA', 'tipo'=>'INGRESO']);
        SubtipoTransaccion::create(['nombre'=>'FINALIZACION DE TAREA','tipo_transaccion_id'=>4]);
        TipoTransaccion::create(['nombre'=>'STOCK INICIAL', 'tipo'=>'INGRESO']);
        SubtipoTransaccion::create(['nombre'=>'STOCK INICIAL','tipo_transaccion_id'=>5]);

        /* EGRESOS */
        TipoTransaccion::create(['nombre'=>'DESPACHO', 'tipo'=>'EGRESO']);
        SubtipoTransaccion::create(['nombre'=>'DESPACHO','tipo_transaccion_id'=>6]);
        TipoTransaccion::create(['nombre'=>'DESPACHO DE TAREA', 'tipo'=>'EGRESO']);
        SubtipoTransaccion::create(['nombre'=>'DESPACHO DE TAREA','tipo_transaccion_id'=>7]);
        TipoTransaccion::create(['nombre'=>'DEVOLUCION A PROVEEDOR', 'tipo'=>'EGRESO']);
        SubtipoTransaccion::create(['nombre'=>'DEVOLUCION A PROVEEDOR','tipo_transaccion_id'=>8]);
        TipoTransaccion::create(['nombre'=>'REPOSICION', 'tipo'=>'EGRESO']);
        SubtipoTransaccion::create(['nombre'=>'REPOSICION','tipo_transaccion_id'=>9]);
        TipoTransaccion::create(['nombre'=>'VENTA', 'tipo'=>'EGRESO']);
        SubtipoTransaccion::create(['nombre'=>'VENTA','tipo_transaccion_id'=>10]);

        /* BIDIRECCIONALES */
        TipoTransaccion::create(['nombre'=>'TRANSFERENCIA ENTRE BODEGAS', 'tipo'=>'INGRESO']);
        SubtipoTransaccion::create(['nombre'=>'TRANSFERENCIA ENTRE BODEGAS','tipo_transaccion_id'=>11]);
        TipoTransaccion::create(['nombre'=>'TRANSFERENCIA ENTRE BODEGAS', 'tipo'=>'EGRESO']);
        SubtipoTransaccion::create(['nombre'=>'TRANSFERENCIA ENTRE BODEGAS','tipo_transaccion_id'=>12]);
        TipoTransaccion::create(['nombre'=>'LIQUIDACION DE MATERIALES', 'tipo'=>'INGRESO']);
        SubtipoTransaccion::create(['nombre'=>'LIQUIDACION DE MATERIALES','tipo_transaccion_id'=>13]);
        TipoTransaccion::create(['nombre'=>'LIQUIDACION DE MATERIALES', 'tipo'=>'EGRESO']);
        SubtipoTransaccion::create(['nombre'=>'LIQUIDACION DE MATERIALES','tipo_transaccion_id'=>14]);
    }
}
