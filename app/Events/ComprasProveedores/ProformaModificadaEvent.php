<?php

namespace App\Events\RecursosHumanos\ComprasProveedores;

use App\Models\ComprasProveedores\Proforma;
use App\Models\Notificacion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class ProformaModificadaEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Proforma $proforma;
    public Notificacion $notificacion;
    public string $url = '/proformas';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($proforma, $informativa)
    {
        $this->proforma = $proforma;

        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->url, TiposNotificaciones::PROFORMA, $proforma->solicitante_id, $proforma->autorizador_id, $proforma, $informativa);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('proformas-modificadas-tracker-' . $this->proforma->autorizador_id);
    }
    public function broadcastAs()
    {
        return 'proforma-event';
    }

    public function obtenerMensaje()
    {
        return $this->proforma->solicitante->nombres . ' ' . $this->proforma->solicitante->apellidos . ' ha modificado la proforma N°' . $this->proforma->id;
    }
}
