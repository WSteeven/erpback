<?php

namespace App\Events;

use App\Models\FondosRotativos\Gasto\Gasto;
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
        $ruta = $gasto->estado == 3? env('SPA_URL', 'http://localhost:8080').'/autorizar-gasto':env('SPA_URL', 'http://localhost:8080').'/notificaciones';
        $this->gasto = $gasto;
        switch ($gasto->estado) {
            case 1:
               $mensaje = 'Te han aprobado un gasto';
                break;
            case 2:
                $mensaje = 'Te han rechazado un gasto';
                break;
            case 3:
                $mensaje = 'Tienes un gasto por aprobar';
                break;
            default:
            $mensaje = 'Tienes un gasto por aprobar';
                break;
        }
        $destinatario = $gasto->estado==3? $gasto->aut_especial:$gasto->id_usuario;
        $remitente = $gasto->estado==3? $gasto->id_usuario:$gasto->aut_especial;
        $this->notificacion = Notificacion::crearNotificacion($mensaje,$ruta, TiposNotificaciones::AUTORIZACION_GASTO, $destinatario, $remitente);
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
        Log::channel('testing')->info('Log', ['nombre canal',$nombre_chanel]);
        return new Channel($nombre_chanel );
    }


    public function broadcastAs()
    {
        return 'fondo-rotativo-event';
    }
}
