<?php

namespace App\Events;

use App\Models\Departamento;
use App\Models\Notificacion;
use App\Models\RecursosHumanos\NominaPrestamos\PermisoEmpleado;
use Exception;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class PermisoNotificacionEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public PermisoEmpleado $permiso;
    public Notificacion $notificacion;
    public int $jefeInmediato = 0;

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Exception
     */
    public function __construct($permiso)
    {
        $ruta = '/empleado';
        $this->permiso = $permiso;
        $this->jefeInmediato = Departamento::where('nombre', Departamento::DEPARTAMENTO_RRHH)->first()->responsable_id;
        $destinatario = $permiso->empleado_id;
        $remitente = $this->jefeInmediato;
        $mensaje = $this->permiso->empleado?->nombres . ' ' . $this->permiso->empleado?->apellidos . ' ha pedido permiso de ' . $this->permiso->fecha_hora_inicio . ' hasta ' . $this->permiso->fecha_hora_fin . ' con la siguiente justificacion: ' . $this->permiso->justificacion;
        $this->notificacion = Notificacion::crearNotificacion($mensaje, $ruta, TiposNotificaciones::PERMISO_EMPLEADO, $destinatario, $remitente, $permiso, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        $nombre_chanel = 'notificacion-permiso-' . $this->jefeInmediato;
        return new Channel($nombre_chanel);
    }

    public function broadcastAs()
    {
        return 'notificacion-permiso-event';
    }
}
