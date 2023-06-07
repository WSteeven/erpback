<?php

namespace App\Events;

use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\Tarea;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class TareaEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Tarea $tarea;
    public Notificacion $notificacion;
    public string $destinatario;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Tarea $tarea, int $emisor, int $destinatario)
    {
        $this->tarea = $tarea;
        $this->destinatario = $destinatario;

        $ruta = '/gasto';
        $coordinador = Empleado::extraerNombresApellidos(Empleado::find($emisor));
        $mensaje = $coordinador . ' ha finalizado la tarea ' . $tarea->codigo_tarea . '. La tarea será visible hasta el ' . Carbon::parse($tarea->updated_at)->addHour(24)->format('d-m-Y H:i:s') . '.';

        $this->notificacion = Notificacion::crearNotificacion($mensaje, $ruta, TiposNotificaciones::TAREA, $emisor, $destinatario, $tarea, true);
    }

    public function broadcastOn()
    {
        $canal = 'subtareas-tracker-' . $this->destinatario;
        return new Channel($canal);
    }

    public function broadcastAs()
    {
        return 'subtarea-event';
    }
}
