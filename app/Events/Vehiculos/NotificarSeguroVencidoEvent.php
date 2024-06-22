<?php

namespace App\Events\Vehiculos;

use App\Models\Notificacion;
use App\Models\User;
use App\Models\Vehiculos\SeguroVehicular;
use App\Models\Vehiculos\Vehiculo;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class NotificarSeguroVencidoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public SeguroVehicular $seguro;
    public Notificacion $notificacion;
    public $url = '/seguros';
    public $tipo;
    public User $admin_vehiculos;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($seguro, $tipo)
    {
        $this->seguro = $seguro;
        $this->tipo = $tipo;
        $this->admin_vehiculos = User::whereHa('roles', function ($query) {
            $query->where('name', User::ROL_ADMINISTRADOR_VEHICULOS);
        })->first();
        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->url, TiposNotificaciones::SEGURO_VEHICULAR, null, $this->admin_vehiculos->empleado->id, $seguro, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('seguros-vehiculares-'.$this->admin_vehiculos->empleado->id);
    }
    public function broadcastAs()
    {
        return 'seguro-event';
    }

    public function obtenerMensaje()
    {
        switch ($this->tipo) {
            case 'vencido':
                return 'El seguro vehicular ' . $this->seguro->nombre . ' con póliza N° ' . $this->seguro->num_poliza . ' está caducacado. Fecha de caducidad: ' . $this->seguro->fecha_caducidad.'. Por favor cambia el seguro en el vehículo asociado';
                break;
            case 'por_vencer':
                return 'El seguro vehicular ' . $this->seguro->nombre . ' con póliza N° ' . $this->seguro->num_poliza . ' está proximo a caducar. Fecha de caducidad: ' . $this->seguro->fecha_caducidad;
                break;
            default:
                return 'Error al obtener el mensaje de seguro vehicular';
        }
    }
}
