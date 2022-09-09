<?php

namespace App\Observers;

use App\Models\Cliente;
use App\Models\CodigoCliente;
use App\Models\Producto;
use Src\Shared\Utils;

class ProductoObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;
    

    /**
     * Handle the Producto "created" event.
     *
     * @param  \App\Models\Producto  $Producto
     * @return void
     */
    public function created(Producto $Producto)
    {
        CodigoCliente::create([
            'codigo'=>'JP-'.Utils::generarCodigo4Digitos($Producto->id),
            'producto_id'=>$Producto->id
        ]);
    }

    /**
     * Handle the Producto "updated" event.
     *
     * @param  \App\Models\Producto  $Producto
     * @return void
     */
    public function updated(Producto $Producto)
    {
        //
    }

    /**
     * Handle the Producto "deleted" event.
     *
     * @param  \App\Models\Producto  $Producto
     * @return void
     */
    public function deleted(Producto $Producto)
    {
        //
    }

    /**
     * Handle the Producto "restored" event.
     *
     * @param  \App\Models\Producto  $Producto
     * @return void
     */
    public function restored(Producto $Producto)
    {
        //
    }

    /**
     * Handle the Producto "force deleted" event.
     *
     * @param  \App\Models\Producto  $Producto
     * @return void
     */
    public function forceDeleted(Producto $Producto)
    {
        //
    }
}
