<?php

namespace App\Events\RecursosHumanos\vehiculos;

use App\Models\Notificacion;
use App\Models\Vehiculos\MantenimientoVehiculo;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class NotificarMantenimientoCreado implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public MantenimientoVehiculo $mantenimiento;
    public Notificacion $notificacion;
    public $url = '/mantenimientos-vehiculos';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(MantenimientoVehiculo $mantenimiento)
    {
        $this->mantenimiento = $mantenimiento;

        $this->notificacion = Notificacion::crearNotificacion(
            'Se ha creado un nuevo mantenimiento para el vehículo ' . $this->mantenimiento->vehiculo->placa . ' . Por favor gestiona el mantenimiento.',
            $this->url,
            TiposNotificaciones::MANTENIMIENTOS_VEHICULOS,
            $this->mantenimiento->empleado_id,
            $this->mantenimiento->supervisor_id,
            $this->mantenimiento,
            true
        );
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('nuevo-mantenimiento-vehiculo-' . $this->mantenimiento->supervisor_id);
    }
    public function broadcastAs()
    {
        return 'mantenimiento-event';
    }
}
