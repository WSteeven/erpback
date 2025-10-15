<?php

namespace App\Events\Vehiculos;

use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\User;
use App\Models\Vehiculos\SeguroVehicular;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\App\EmpleadoService;
use Src\Config\TiposNotificaciones;
use Throwable;

class NotificarSeguroVencidoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public SeguroVehicular $seguro;
    public Notificacion $notificacion;
    public string $url = '/seguros';
    public string $tipo;
    public Empleado $admin_vehiculos;

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Throwable
     */
    public function __construct($seguro, $tipo)
    {
        $this->seguro = $seguro;
        $this->tipo = $tipo;
        $this->admin_vehiculos = EmpleadoService::obtenerEmpleadoRolEspecifico(User::ROL_ADMINISTRADOR_VEHICULOS);

        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->url, TiposNotificaciones::SEGURO_VEHICULAR, null, $this->admin_vehiculos->id, $seguro, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('seguros-vehiculares-'.$this->admin_vehiculos->id);
    }
    public function broadcastAs()
    {
        return 'seguro-event';
    }

    public function obtenerMensaje()
    {
        return match ($this->tipo) {
            'vencido' => 'El seguro vehicular ' . $this->seguro->nombre . ' con póliza N° ' . $this->seguro->num_poliza . ' está caducacado. Fecha de caducidad: ' . $this->seguro->fecha_caducidad . '. Por favor cambia el seguro en el vehículo asociado',
            'por_vencer' => 'El seguro vehicular ' . $this->seguro->nombre . ' con póliza N° ' . $this->seguro->num_poliza . ' está proximo a caducar. Fecha de caducidad: ' . $this->seguro->fecha_caducidad,
            default => 'Error al obtener el mensaje de seguro vehicular',
        };
    }
}
