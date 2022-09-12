<?php

namespace App\Observers;

use App\Models\Cliente;
use App\Models\CodigoCliente;
use App\Models\Producto;
use Illuminate\Support\Facades\Log;
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
     * Se crea un codigo de producto unico para JP para el producto recien creado
     *
     * @param  \App\Models\Producto  $Producto
     * @return void
     */
    public function created(Producto $producto)
    {
        CodigoCliente::create([
            'codigo'=>'JP-'.Utils::generarCodigo4Digitos($producto->id),
            'producto_id'=>$producto->id
        ]);
        /* Log::channel('testing')->info('Log despues de crear', ['producto', $producto->id, 'nombre', $producto->nombre, 'categoria',$producto->categoria_id]); */
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
