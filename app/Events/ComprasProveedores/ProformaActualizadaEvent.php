<?php

namespace App\Events\ComprasProveedores;

use App\Models\Autorizacion;
use App\Models\ComprasProveedores\Proforma;
use App\Models\Notificacion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Src\Config\TiposNotificaciones;

class ProformaActualizadaEvent implements ShouldBroadcast
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
        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->url, TiposNotificaciones::PROFORMA, $proforma->autorizador_id, $proforma->solicitante_id, $proforma, $informativa);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('proformas-actualizadas-tracker-' . $this->proforma->solicitante_id);
    }
    public function broadcastAs()
    {
        return 'proforma-event';
    }

    public function obtenerMensaje()
    {
        if ($this->proforma->autorizacion->nombre == Autorizacion::APROBADO)
            return $this->proforma->autorizador->nombres . ' ' . $this->proforma->autorizador->apellidos . ' ha aprobado tu proforma N° ' . $this->proforma->id;
        if ($this->proforma->autorizacion->nombre == Autorizacion::CANCELADO)
            return $this->proforma->autorizador->nombres . ' ' . $this->proforma->autorizador->apellidos . ' ha anulado tu proforma N° ' . $this->proforma->id;

        return $this->proforma->autorizador->nombres . ' ' . $this->proforma->autorizador->apellidos . ' ha modificado la proforma que generaste. Proforma N° ' . $this->proforma->id;
    }
}
