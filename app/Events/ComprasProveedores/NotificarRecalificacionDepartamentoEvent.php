<?php

namespace App\Events\ComprasProveedores;

use App\Models\ComprasProveedores\DetalleDepartamentoProveedor;
use App\Models\Departamento;
use App\Models\Notificacion;
use App\Models\Proveedor;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;
use Throwable;

class NotificarRecalificacionDepartamentoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Notificacion $notificacion;
    public string $url = '/proveedores';

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Throwable
     */
    public function __construct(DetalleDepartamentoProveedor $entidad)
    {
        $departamento = Departamento::find($entidad->departamento_id);
        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje($entidad->proveedor), $this->url, TiposNotificaciones::PROVEEDOR, null, $departamento->responsable_id, $entidad, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('proveedores-tracker-' . $this->notificacion->per_destinatario_id);
    }

    public function broadcastAs()
    {
        return 'proveedor-recalificado-event';
    }

    private function obtenerMensaje(Proveedor $proveedor)
    {
        $nombre_proveedor = $proveedor->empresa->nombre_comercial ?? $proveedor->empresa->razon_social ?? '';
        return "Es momento de realizar la recalificación del proveedor {$nombre_proveedor} - {$proveedor->sucursal}. Por favor realiza la recalificación del proveedor";
    }
}
