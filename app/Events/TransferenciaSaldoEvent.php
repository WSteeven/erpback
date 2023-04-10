<?php

namespace App\Events;

use App\Http\Resources\UserResource;
use App\Models\Empleado;
use App\Models\FondosRotativos\Saldo\Transferencias;
use App\Models\Notificacion;
use App\Models\User;
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
    public $esContabilidad = false;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($transferencia, $es_contabilidad = false)
    {
        $ruta = $transferencia->estado == 3? '/autorizar-transferencia':'/transferencia';
        $this->transferencia = $transferencia;

        $destinatario = $transferencia->estado!=3? $transferencia->usuario_recibe_id: $transferencia->usuario_envia_id;
        $remitente = 0;
        $this->esContabilidad = $es_contabilidad;
        $usuario_envia = Empleado::where('id', $transferencia->usuario_envia_id)->first();
        $usuario_recibe = Empleado::where('id', $transferencia->usuario_recibe_id)->first();
        $mensaje = '';
        if ($es_contabilidad == true) {
            $remitente =  10;
            switch ($transferencia->estado) {
                case 1:
                    $mensaje = $usuario_recibe->nombres.' '.$usuario_recibe->apellidos.'Acepto Transferencia';
                    break;
                case 2:
                    $mensaje = 'Han rechazadoa  transferencia de  ' . $usuario_envia->nombres . ' ' . $usuario_envia->apellidos . ' a ' . $usuario_recibe->nombres . ' ' . $usuario_envia->apellidos . ' por un monto de $' . $transferencia->monto;
                    break;
                case 3:
                    $mensaje = 'Han realizado una  transferencia de  ' . $usuario_envia->nombres . ' ' . $usuario_envia->apellidos . ' a ' . $usuario_recibe->nombres . ' ' . $usuario_envia->apellidos . ' por un monto de $' . $transferencia->monto;
                    break;
                default:
                    $mensaje = 'Tienes un transferencia por aceptar';
                    break;
            }
        }else{
            $remitente = $transferencia->estado!=3? $transferencia->usuario_envia_id:$transferencia->usuario_recibe_id;
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
        }
        $this->notificacion = Notificacion::crearNotificacion($mensaje,$ruta, TiposNotificaciones::AUTORIZACION_GASTO, $destinatario, $remitente);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        Log::channel('testing')->info('Log', ['es_contabilidad_chanel',$this->esContabilidad]);
        if ($this->esContabilidad) {
            $nombre_chanel =  'transferencia-saldo-' . 10;
        } else {
            $nombre_chanel =  $this->transferencia->estado == 3 ? 'transferencia-saldo-' . $this->transferencia->usuario_recibe_id : 'transferencia-saldo-' . $this->transferencia->usuario_envia_id;
        }
        return new Channel($nombre_chanel);

    }
    public function broadcastAs()
    {
        return 'transferencia-saldo-event';
    }
}
