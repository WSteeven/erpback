<?php

namespace App\Events\Vehiculos;

use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\User;
use App\Models\Vehiculos\MantenimientoVehiculo;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\App\EmpleadoService;
use Src\Config\TiposNotificaciones;

class NotificarMantenimientoPendienteRetrasadoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public MantenimientoVehiculo $mantenimiento;
    public Notificacion $notificacion;
    public $url = '/mantenimientos-vehiculos';
    private Empleado $admin_vehiculos;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($mantenimiento)
    {
        $this->mantenimiento = $mantenimiento;
        $this->admin_vehiculos = EmpleadoService::obtenerEmpleadoRolEspecifico(User::ROL_ADMINISTRADOR_VEHICULOS, true);

        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->url, TiposNotificaciones::MANTENIMIENTOS_VEHICULOS, null, $this->admin_vehiculos->id, $this->mantenimiento, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('mantenimientos-vehiculos-' . $this->admin_vehiculos->id);
    }

    public function broadcastAs()
    {
        return 'mantenimiento-event';
    }

    public function obtenerMensaje()
    {
        switch ($this->mantenimiento->estado) {
            case MantenimientoVehiculo::PENDIENTE:
                return 'El vehículo con placas ' . $this->mantenimiento->vehiculo->placa . ' tiene un mantenimiento preventivo ' . $this->mantenimiento->servicio->nombre . ' ' . MantenimientoVehiculo::PENDIENTE . ' de realizar. Por favor atiende y gestiona el mantenimiento.';
            case MantenimientoVehiculo::RETRASADO:
                return 'El vehículo con placas ' . $this->mantenimiento->vehiculo->placa . ' tiene un mantenimiento preventivo ' . $this->mantenimiento->servicio->nombre . ' ' . MantenimientoVehiculo::RETRASADO . ' . Según el plan de mantenimientos, el vehículo lleva ' . $this->mantenimiento->km_retraso . ' kms de retraso respecto al último mantenimiento.';
            default:
                return 'Tienes un mantenimiento de vehículos que gestionar';
        }
    }
}
