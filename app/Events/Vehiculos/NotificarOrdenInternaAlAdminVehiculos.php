<?php

namespace App\Events\RecursosHumanos\Vehiculos;

use App\Models\Notificacion;
use App\Models\User;
use App\Models\Vehiculos\OrdenReparacion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use Spatie\Permission\Models\Role;
use Src\App\EmpleadoService;
use Src\Config\TiposNotificaciones;

class NotificarOrdenInternaAlAdminVehiculos implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $ruta = '/ordenes-reparaciones';
    public Notificacion $notificacion;
    public OrdenReparacion $orden;
    public int $admin_vehiculos;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($orden)
    {
        $this->orden = $orden;
        $this->admin_vehiculos = EmpleadoService::obtenerEmpleadoRolEspecifico(User::ROL_ADMINISTRADOR_VEHICULOS)->id;

        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->ruta, TiposNotificaciones::ORDEN_REPARACION_VEHICULO, $this->orden->solicitante_id, $this->admin_vehiculos, $this->orden, 1);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('ordenes-reparaciones-' . $this->admin_vehiculos);
    }

    public function broadcastAs()
    {
        return 'ordenes-creadas';
    }

    private function obtenerMensaje()
    {
        return $this->orden->solicitante->nombres . ' ' . $this->orden->solicitante->apellidos . ' ha solicitado una orden de reparación de vehículo con placa ' . $this->orden->vehiculo->placa . '. Por favor aprueba o cancela la solicitud';
    }
}
