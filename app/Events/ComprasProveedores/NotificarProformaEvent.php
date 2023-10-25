<?php

namespace App\Events\ComprasProveedores;

use App\Models\ComprasProveedores\Proforma;
use App\Models\Notificacion;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;
use Src\Shared\Utils;

class NotificarProformaEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public Proforma $proforma;
    public Notificacion $notificacion;
    public string $url = '/proformas';
    public int $diasRestantes;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($proforma)
    {
        $this->proforma = $proforma;
        $this->diasRestantes = Utils::obtenerNumeroEnCadena($this->proforma->tiempo)+3;

        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->url, TiposNotificaciones::PROFORMA, null, $proforma->solicitante_id, $proforma, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('proformas-vencidas-tracker-' . $this->proforma->solicitante_id);
    }
    public function broadcastAs()
    {
        return 'proforma-event';
    }

    public function obtenerMensaje()
    {
        return 'La proforma N°' . $this->proforma->id . ' que generaste el ' . date('Y-m-d h:i:s a', strtotime($this->proforma->created_at)) . ' ha llegado a su fecha de vencimiento, por favor anula o convierte en prefactura la proforma. Quedan ' . Utils::obtenerDiasRestantes($this->proforma->created_at, $this->diasRestantes) . ' días para anulación automatica';
    }
}
