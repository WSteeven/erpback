<?php

namespace App\Events;

use App\Http\Resources\UserInfoResource;
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
use Illuminate\Support\Facades\Auth;
use Src\Config\TiposNotificaciones;

class TransferenciaSaldoContabilidadEvent implements ShouldBroadcast
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
        $ruta = '/transferencia';
        $this->transferencia = $transferencia;
        $destinatario = $transferencia->estado!=3? $transferencia->usuario_recibe_id: $transferencia->usuario_envia_id;
        $usuario_envia = Empleado::where('id', $transferencia->usuario_envia_id)->first();
        $usuario_recibe = Empleado::where('id', $transferencia->usuario_recibe_id)->first();
        $informativa = false;
        switch ($transferencia->estado) {
            case 1:
                $mensaje = $usuario_recibe->nombres.' '.$usuario_recibe->apellidos.'Acepto Transferencia';
                break;
            case 2:
                $mensaje = 'Han rechazadoa  transferencia de  ' . $usuario_envia->nombres . ' ' . $usuario_envia->apellidos . ' a ' . $usuario_recibe->nombres . ' ' . $usuario_recibe->apellidos . ' por un monto de $' . $transferencia->monto;
                break;
            case 3:
                $mensaje = 'Han realizado una  transferencia de  ' . $usuario_envia->nombres . ' ' . $usuario_envia->apellidos . ' a ' . $usuario_recibe->nombres . ' ' . $usuario_recibe->apellidos . ' por un monto de $' . $transferencia->monto;
                break;
            default:
            $informativa = true;
                $mensaje = 'Tienes un transferencia por aceptar';
                break;
        }
        // recorrer usuarios de Rol CONTABILIDAD
        $empleados_contabilidad = User::role('CONTABILIDAD')->where('users.id', '!=', Auth::user()->id)->orderby('users.name', 'asc')->get();
        $empleados_contabilidad = UserInfoResource::collection($empleados_contabilidad);
        foreach ($empleados_contabilidad as $empleado) {
            $this->notificar($mensaje, $ruta,$destinatario, $empleado->id,$informativa);
        }
    }
    public function notificar($mensaje, $ruta, $destinatario, $remitente, $informativa = false)
    {
        $this->notificacion = Notificacion::crearNotificacion($mensaje,$ruta, TiposNotificaciones::AUTORIZACION_GASTO, $destinatario, $remitente,$this->transferencia,$informativa);
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $nombre_chanel =  'transferencia-saldo-contabilidad-' . 6;
        return new Channel($nombre_chanel);
    }
    public function broadcastAs()
    {
        return 'transferencia-saldo-contabilidad-event';
    }
}
