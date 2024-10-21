<?php

namespace App\Events\RecursosHumanos\Vehiculos;

use App\Models\Notificacion;
use App\Models\Vehiculos\TransferenciaVehiculo;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class NotificarTransferenciaVehiculoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public TransferenciaVehiculo $transferencia;
    public Notificacion $notificacion;
    public $url = '/transferencias-vehiculos';
    public int $destinatario;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($transferencia)
    {
        $this->transferencia = $transferencia;

        $this->notificacion = $this->obtenerNotificacion();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('transferencia-vehiculo-' . $this->destinatario);
    }
    public function broadcastAs()
    {
        return 'transferencia-vehiculo-event';
    }

    public function obtenerNotificacion()
    {
        switch ($this->transferencia->estado) {
            case 'PENDIENTE':
                $this->destinatario = $this->transferencia->responsable_id;
                $msg = $this->transferencia->entrega->empleado?->nombres . ' ' . $this->transferencia->entrega->empleado?->apellidos . ' le ha realizado una transferencia de vehículo con placas: ' . $this->transferencia->vehiculo->placa . '. Por favor acepta y firma la transferencia';
                return Notificacion::crearNotificacion($msg, $this->url, TiposNotificaciones::TRANSFERENCIA_VEHICULO, $this->transferencia->entrega_id, $this->transferencia->responsable_id, $this->transferencia, false);
            case 'ACEPTADO':
                $this->destinatario = $this->transferencia->entrega_id;
                $msg = auth()->user()->empleado->nombres . ' ' . auth()->user()->empleado->apellidos . ' ha aceptado la transferencia de vehículo con placas: ' . $this->transferencia->vehiculo->placa . '.';
                return Notificacion::crearNotificacion($msg, $this->url, TiposNotificaciones::TRANSFERENCIA_VEHICULO, $this->transferencia->responsable_id, $this->transferencia->entrega_id, $this->transferencia, false);
            default: //rechazado
                $this->destinatario = $this->transferencia->entrega_id;
                $msg = auth()->user()->empleado->nombres . ' ' . auth()->user()->empleado->apellidos . ' ha rechazado la transferencia de vehículo con placas: ' . $this->transferencia->vehiculo->placa . '.';
                return Notificacion::crearNotificacion($msg, $this->url, TiposNotificaciones::TRANSFERENCIA_VEHICULO, $this->transferencia->responsable_id, $this->transferencia->entrega_id, $this->transferencia, false);
        }
    }
}
