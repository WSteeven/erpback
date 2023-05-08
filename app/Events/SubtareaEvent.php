<?php

namespace App\Events;

use App\Models\Empleado;
use App\Models\MotivoSuspendido;
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
use Illuminate\Support\Facades\DB;

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

        $ruta = '/trabajo-agendado';

        $originador = $this->obtenerRemitente($rolReceptor);
        $destinatario = $this->obtenerDestinatario($rolReceptor);
        $mensaje = $this->obtenerMensaje();

        $this->notificacion = Notificacion::crearNotificacion($mensaje, $ruta, TiposNotificaciones::SUBTAREA, $originador, $destinatario, $subtarea, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
        $canal = 'subtareas-tracker-' . $this->obtenerDestinatario($this->rolReceptor);
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
                return Empleado::extraerNombresApellidos($this->subtarea->tarea->coordinador) . ' le ha agendado la subtarea ' . $this->subtarea->codigo_subtarea . '.';
            case Subtarea::EJECUTANDO:
                return Empleado::extraerNombresApellidos($this->subtarea->empleado) . ' ha comenzado a EJECUTAR la subtarea ' . $this->subtarea->codigo_subtarea . '.';
            case Subtarea::PAUSADO:
                return Empleado::extraerNombresApellidos($this->subtarea->empleado) . ' ha PAUSADO la subtarea ' . $this->subtarea->codigo_subtarea . '.';
            case Subtarea::REALIZADO:
                return Empleado::extraerNombresApellidos($this->subtarea->empleado) . ' ha REALIZADO la subtarea ' . $this->subtarea->codigo_subtarea . '.';
            case Subtarea::SUSPENDIDO:
                $motivo = DB::table('motivo_suspendido_subtarea')->where('subtarea_id', $this->subtarea->id)->latest()->first();
                Log::channel('testing')->info('Log', compact('motivo'));
                return Empleado::extraerNombresApellidos($this->subtarea->empleado) . ' ha SUSPENDIDO la subtarea ' . $this->subtarea->codigo_subtarea . '. por el motivo "' . MotivoSuspendido::find($motivo->motivo_suspendido_id)->motivo . '"';
        }
    }
}
