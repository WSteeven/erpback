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
        TipoTransaccion::create(['nombre'=>'COMPRA A PROVEEDOR', 'tipo'=>'INGRESO']);
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

        /* EGRESOS */
        TipoTransaccion::create(['nombre'=>'DESPACHO', 'tipo'=>'EGRESO']);
        TipoTransaccion::create(['nombre'=>'DESPACHO DE TAREA', 'tipo'=>'EGRESO']);
        TipoTransaccion::create(['nombre'=>'DEVOLUCION A PROVEEDOR', 'tipo'=>'EGRESO']);
        TipoTransaccion::create(['nombre'=>'REPOSICION', 'tipo'=>'EGRESO']);
        TipoTransaccion::create(['nombre'=>'VENTA', 'tipo'=>'EGRESO']);

        /* BIDIRECCIONALES */
        TipoTransaccion::create(['nombre'=>'TRANSFERENCIA ENTRE BODEGAS', 'tipo'=>'INGRESO']);
        TipoTransaccion::create(['nombre'=>'TRANSFERENCIA ENTRE BODEGAS', 'tipo'=>'EGRESO']);
        TipoTransaccion::create(['nombre'=>'LIQUIDACION DE MATERIALES', 'tipo'=>'INGRESO']);
        TipoTransaccion::create(['nombre'=>'LIQUIDACION DE MATERIALES', 'tipo'=>'EGRESO']);
    }
}
