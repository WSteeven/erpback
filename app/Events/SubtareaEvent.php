<?php

namespace App\Events;

use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\Subtarea;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Src\Config\TiposNotificaciones;

class SubtareaEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // public string $mensaje;
    public Subtarea $subtarea;
    public Notificacion $notificacion;
    public string $rolReceptor;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Subtarea $subtarea, string $rolReceptor)
    {
        $this->subtarea = $subtarea;
        $this->rolReceptor = $rolReceptor;

        $ruta = env('SPA_URL', 'https://sistema.jpconstrucred.com') . '/tarea';

        $originador = $this->obtenerRemitente($rolReceptor);
        $destinatario = $this->obtenerDestinatario($rolReceptor);
        $mensaje = $this->obtenerMensaje();

        $this->notificacion = Notificacion::crearNotificacion($mensaje, $ruta, TiposNotificaciones::SUBTAREA, $originador, $destinatario, $subtarea);
    }

    /* public static function crearNotificacion($mensaje, $originador, $destinatario)
    {
        $notificacion = Notificacion::create([
            'mensaje' => $mensaje,
            'link' => 'subtareas',
            'per_originador_id' => $originador,
            'per_destinatario_id' => $destinatario,
            'tipo_notificacion' => TiposNotificaciones::SUBTAREA,
        ]);
        return $notificacion;
    }*/

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
        $canal = 'subtareas-tracker-15'; //. $this->obtenerDestinatario($this->rolReceptor);
        return new Channel($canal);
    }

    /*public function broadcastWith()
    {
        $extra = [
            'campo1' => 'Mensaje numero #1',
            'campo2' => 'Mensaje numero #2',
        ];

        return $extra;
    }*/

    public function broadcastAs()
    {
        return 'subtarea-event';
    }

    private function obtenerRemitente(string $rolReceptor)
    {
        switch ($rolReceptor) {
            case User::ROL_TECNICO:
                return $this->subtarea->tarea->coordinador_id;
            case User::ROL_COORDINADOR:
                return $this->subtarea->empleado_id;
        }
    }

    private function obtenerDestinatario(string $rolReceptor)
    {
        Log::channel('testing')->info('Log', compact('rolReceptor'));
        switch ($rolReceptor) {
            case User::ROL_TECNICO:
                Log::channel('testing')->info('Log', ['mensaje' => 'es tecnico']);
                Log::channel('testing')->info('Log', ['id empleado' => $this->subtarea->empleado_id]);
                return $this->subtarea->empleado_id;
            case User::ROL_COORDINADOR:
                Log::channel('testing')->info('Log', ['mensaje' => 'es coordinador']);
                Log::channel('testing')->info('Log', ['id coordinador' => $this->subtarea->tarea->coordinador_id]);
                return $this->subtarea->tarea->coordinador_id;
        }
    }

    private function obtenerMensaje()
    {
        switch ($this->subtarea->estado) {
            case Subtarea::AGENDADO:
                return Empleado::extraerNombresApellidos($this->subtarea->tarea->coordinador) . ' le ha agendado la subtarea ' . $this->subtarea->codigo_subtarea;
        }
    }
}
