<?php

namespace App\Events\Vehiculos;

use App\Models\Notificacion;
use App\Models\Vehiculos\Matricula;
use Exception;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class NotificarMatriculacionVehicularEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public Matricula $matricula;
    public Notificacion $notificacion;
    public string $url = '/matriculas';
    public string $tipo;

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Exception
     */
    public function __construct($matricula, $tipo)
    {
        $this->matricula = $matricula;
        $this->tipo = $tipo;

        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->url, TiposNotificaciones::MATRICULA, null, null, $this->matricula, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('matriculas-vehiculos');
    }
    public function broadcastAs()
    {
        return 'matricula-event';
    }

    public function obtenerMensaje()
    {
        switch ($this->tipo) {
            case 'mes':
                return 'El vehículo con placa ' . $this->matricula->vehiculo->placa . ' debe cumplir con la matriculación vehicular anual durante este mes. Se estará notificando diariamente hasta que haya realizado la respectiva matriculación';
            case 'vencidas':
                return 'El vehículo con placa ' . $this->matricula->vehiculo->placa . ' no ha sido matriculado aún y está rezagado según el calendario de matriculación establecido. Por favor, matricula el vehículo y evita multas';
            default:
                return 'Error al obtener el mensaje de la matriculación vehicular';
        }
    }
}
