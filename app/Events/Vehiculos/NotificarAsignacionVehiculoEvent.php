<?php

namespace App\Events\RecursosHumanos\Vehiculos;

use App\Models\Notificacion;
use App\Models\Vehiculos\AsignacionVehiculo;
use Exception;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class NotificarAsignacionVehiculoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public AsignacionVehiculo $asignacion;
    public Notificacion $notificacion;
    public string $url = '/asignaciones-vehiculos';
    public int $destinatario;

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Exception
     */
    public function __construct($asignacion)
    {
        $this->asignacion = $asignacion;

        $this->notificacion = $this->obtenerNotificacion();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('asignacion-vehiculo-' . $this->destinatario);
    }

    public function broadcastAs()
    {
        return 'asignacion-vehiculo-event';
    }

    /**
     * @throws Exception
     */
    public function obtenerNotificacion()
    {
        switch ($this->asignacion->estado) {
            case 'PENDIENTE':
                $this->destinatario = $this->asignacion->responsable_id;
                $msg = $this->asignacion->entrega->empleado?->nombres . ' ' . $this->asignacion->entrega->empleado?->apellidos . ' ha realizado una asignación de vehículo con placas: ' . $this->asignacion->vehiculo->placa . '. Por favor acepta y firma el comprobante';
                return Notificacion::crearNotificacion($msg, $this->url, TiposNotificaciones::ASIGNACION_VEHICULO, $this->asignacion->entrega_id, $this->asignacion->responsable_id, $this->asignacion, false);
            case 'ACEPTADO':
                $this->destinatario = $this->asignacion->entrega_id;
                $msg = auth()->user()->empleado->nombres . ' ' . auth()->user()->empleado->apellidos . ' ha aceptado la asignación de vehículo con placas: ' . $this->asignacion->vehiculo->placa . '.';
                return Notificacion::crearNotificacion($msg, $this->url, TiposNotificaciones::ASIGNACION_VEHICULO, $this->asignacion->responsable_id, $this->asignacion->entrega_id, $this->asignacion, false);
            default: //rechazado
                $this->destinatario = $this->asignacion->entrega_id;
                $msg = auth()->user()->empleado->nombres . ' ' . auth()->user()->empleado->apellidos . ' ha rechazado la asignación de vehículo con placas: ' . $this->asignacion->vehiculo->placa . '.';
                return Notificacion::crearNotificacion($msg, $this->url, TiposNotificaciones::ASIGNACION_VEHICULO, $this->asignacion->responsable_id, $this->asignacion->entrega_id, $this->asignacion, false);
        }
    }
}
