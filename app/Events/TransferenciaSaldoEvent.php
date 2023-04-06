<?php

namespace App\Events;

use App\Models\Empleado;
use App\Models\FondosRotativos\Saldo\Transferencias;
use App\Models\Notificacion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Src\Config\TiposNotificaciones;

class TransferenciaSaldoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public Transferencias $transferencia;
    public Notificacion $notificacion;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($transferencia)
    {
        $ruta = $transferencia->estado == 3? '/autorizar-transferencia':'/transferencia';
        $this->transferencia = $transferencia;
        switch ($transferencia->estado) {
            case 1:
               $mensaje = 'Te han aceptado una Transferencia';
                break;
            case 2:
                $mensaje = 'Te han rechazado una transferencia';
                break;
            case 3:
                $mensaje = 'Tienes una transferencia por aceptar';
                break;
            default:
            $mensaje = 'Tienes un gasto por aceptar';
                break;
        }
        $destinatario = $transferencia->estado!=3? $transferencia->usuario_recibe_id: $transferencia->usuario_envia_id;
        $remitente = $transferencia->estado!=3? $transferencia->usuario_envia_id:$transferencia->usuario_recibe_id;
        $this->notificacion = Notificacion::crearNotificacion($mensaje,$ruta, TiposNotificaciones::AUTORIZACION_GASTO, $destinatario, $remitente);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $nombre_chanel =  $this->transferencia->estado==3? 'transferencia-saldo-'. $this->transferencia->usuario_recibe_id:'transferencia-saldo-'. $this->transferencia->usuario_envia_id;
        return new Channel($nombre_chanel );
    }
    public function broadcastAs()
    {
        return 'transferencia-saldo-event';
    }
}
