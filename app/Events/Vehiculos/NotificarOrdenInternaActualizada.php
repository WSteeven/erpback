<?php

namespace App\Events\Vehiculos;

use App\Models\Notificacion;
use App\Models\Vehiculos\OrdenReparacion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class NotificarOrdenInternaActualizada implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $ruta = '/ordenes-reparaciones';
    public Notificacion $notificacion;
    public OrdenReparacion $orden;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($orden)
    {
        $this->orden = $orden;

        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->ruta, TiposNotificaciones::ORDEN_REPARACION_VEHICULO, auth()->user()->empleado->id, $this->orden->solicitante_id, $this->orden, 1);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('ordenes-reparaciones-' . $this->orden->solicitante_id);
    }

    public function broadcastAs()
    {
        return 'ordenes-actualizadas';
    }

    private function obtenerMensaje()
    {
        switch ($this->orden->autorizacion_id) {
            case 2:
                return $this->orden->solicitante->nombres . ' ' . $this->orden->solicitante->apellidos . ' ha solicitado una orden de reparación de vehículo con placa ' . $this->orden->vehiculo->placa . '. Por favor aprueba o cancela la solicitud';
            case 3:
                return $this->orden->solicitante->nombres . ' ' . $this->orden->solicitante->apellidos . ' ha solicitado una orden de reparación de vehículo con placa ' . $this->orden->vehiculo->placa . '. Por favor aprueba o cancela la solicitud';
            default:
                return auth()->user()->empleado->nombres . ' ' . auth()->user()->empleado->apellidos . ' ha ' . $this->orden->autorizacion->nombre . ' la orden de reparación';
        }
    }
}
