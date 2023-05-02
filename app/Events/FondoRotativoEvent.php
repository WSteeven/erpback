<?php

namespace App\Events;

use App\Http\Resources\FondosRotativos\Gastos\GastoResource;
use App\Models\Empleado;
use App\Models\FondosRotativos\Gasto\DetalleViatico;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\FondosRotativos\Gasto\SubdetalleGasto;
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

class FondoRotativoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Gasto $gasto;
    public Notificacion $notificacion;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($gasto)
    {
        $ruta = $gasto->estado == 3? '/autorizar-gasto':'/gasto';
        $this->gasto = $gasto;
        $informativa = false;
        switch ($gasto->estado) {
            case 1:
                $informativa = true;
               $mensaje = 'Te han aprobado un gasto';
                break;
            case 2:
                $informativa = true;
                $mensaje = 'Te han rechazado un gasto por el siguiente motivo: '.$gasto->detalle_estado;
                break;
            case 3:
                $mensaje = $this->mostrar_mensaje($gasto);
                break;
            default:
            $mensaje = 'Tienes un gasto por aprobar';
                break;
        }
        $destinatario = $gasto->estado!=3? $gasto->aut_especial:$gasto->id_usuario;
        $remitente = $gasto->estado!=3? $gasto->id_usuario:$gasto->aut_especial;
      $this->notificacion = Notificacion::crearNotificacion($mensaje,$ruta, TiposNotificaciones::AUTORIZACION_GASTO, $destinatario, $remitente,$gasto,$informativa);
    }
    public function mostrar_mensaje($gasto)
    {
        $empleado = Empleado::find($gasto->id_usuario);
        $modelo = new GastoResource($gasto);
        $detalle = $modelo->detalle_info->descripcion;
        $sub_detalle_info =$this->subdetalle_info($modelo->sub_detalle_info);
        $mensaje = $empleado->nombres.' '.$empleado->apellidos.' ha solicitado un gasto por un monto de $'.$gasto->total.'con respecto a '.$detalle.' '.$sub_detalle_info;
        return $mensaje;
    }
    private function subdetalle_info($subdetalle_info){
        $descripcion = '';
        $i=0;
        foreach($subdetalle_info as $sub_detalle){
            $descripcion .= $sub_detalle->descripcion;
            $i++;
            if ($i !== count($subdetalle_info)) {
                $descripcion .= ', ';
            }
        }
        return $descripcion;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //return new PrivateChannel('channel-name');
        $nombre_chanel =  $this->gasto->estado==3? 'fondo-rotativo-'. $this->gasto->aut_especial:'fondo-rotativo-'. $this->gasto->id_usuario;
        return new Channel($nombre_chanel );
    }


    public function broadcastAs()
    {
        return 'fondo-rotativo-event';
    }
}
