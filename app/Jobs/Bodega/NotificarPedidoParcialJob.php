<?php

namespace App\Jobs\Bodega;

use App\Events\RecursosHumanos\Bodega\NotificarPedidoParcial as BodegaNotificarPedidoParcial;
use App\Models\EstadoTransaccion;
use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Src\Config\EstadosTransacciones;

class NotificarPedidoParcialJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $cantidad = Pedido::where('estado_id', EstadosTransacciones::PARCIAL)->count();
            $pedido = Pedido::where('estado_id', EstadosTransacciones::PARCIAL)->orderBy('updated_at', 'desc')->first();
            if ($cantidad > 0) {
                event(new BodegaNotificarPedidoParcial($pedido, $cantidad));
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
