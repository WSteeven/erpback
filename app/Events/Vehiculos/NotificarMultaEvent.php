<?php

namespace App\Events\Vehiculos;

use App\Models\Departamento;
use App\Models\Notificacion;
use App\Models\Vehiculos\MultaConductor;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\Departamentos;
use Src\Config\TiposNotificaciones;

class NotificarMultaEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $ruta = '/multas-conductores';
    public Notificacion $notificacion;
    public MultaConductor $multa;
    public int $responsable_rrhh;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($multa)
    {
        $this->multa = $multa;
        $this->responsable_rrhh = Departamento::find(Departamentos::RR_HH)?->responsable_id;
        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->ruta, TiposNotificaciones::MULTA_CONDUCTOR, $multa->empleado_id, $this->responsable_rrhh, $multa, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('multas-choferes-' . $this->responsable_rrhh);
    }
    public function broadcastAs()
    {
        return 'multas-choferes-event';
    }

    private function obtenerMensaje()
    {
        return  'Se ha generado una multa de tránsito con afectación al empleado para ' . $this->multa->conductor->empleado->nombres . ' ' . $this->multa->conductor->empleado->apellidos . ' por el siguiente motivo: ' . $this->multa->comentario . ' en la siguente fecha: ' . $this->multa?->fecha_infraccion . ' por el siguiente valor: $' . $this->multa?->total . ' con número de placa: ' . $this->multa?->placa . '. Por favor registra dichos valores!';
    }
}
