<?php

namespace App\Events;

use App\Models\FondosRotativos\Saldo\Transferencias;
use App\Models\Notificacion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Src\Config\TiposNotificaciones;

class TransferenciaSaldoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public Transferencias $transferencia;
    public Notificacion $notificacion;
    private String $nombre_canal;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Transferencias $transferencia)
    {
        $this->transferencia = $transferencia;
        Log::channel('testing')->info('Log', ['transferencia',  $transferencia]);

        $ruta = $this->obtenerRuta();
        $this->nombre_canal = $this->obtenerNombreCanal();
        $this->notificacion = Notificacion::crearNotificacion(
            $ruta['mensaje'],
            $ruta['ruta'],
            TiposNotificaciones::TRANSFERENCIA_SALDO,
            $ruta['originador'],
            $ruta['destinatario'],
            $transferencia,
            $ruta['informativa']
        );
    }
    public function obtenerRuta()
    {
        $ruta = null;
        switch ($this->transferencia->estado) {
            case Transferencias::APROBADO:
                $ruta = [
                    'ruta' => '/transferencia',
                    'informativa' => true,
                    'mensaje' => 'Te han aceptado una Transferencia',
                    'originador' =>   $this->transferencia->usuario_recibe_id,
                    'destinatario' =>  $this->transferencia->usuario_envia_id,
                ];
                break;
            case Transferencias::RECHAZADO:
                $ruta = [
                    'ruta' => '/transferencia',
                    'informativa' => true,
                    'mensaje' => 'Te han rechazado una transferencia',
                    'originador' =>   $this->transferencia->usuario_recibe_id,
                    'destinatario' =>  $this->transferencia->usuario_envia_id,
                ];
                break;
            case Transferencias::PENDIENTE:
                $ruta = [
                    'ruta' => '/autorizar-transferencia',
                    'informativa' => false,
                    'mensaje' => 'Tienes una transferencia por aceptar',
                    'originador' =>  $this->transferencia->usuario_envia_id,
                    'destinatario' => $this->transferencia->usuario_recibe_id,
                ];
                break;
            case Transferencias::ANULADO:
                $ruta = [
                    'ruta' => '/transferencia',
                    'informativa' => false,
                    'mensaje' => 'Te han anulado Transferencia',
                    'originador' =>  $this->transferencia->usuario_recibe_id,
                    'destinatario' => $this->transferencia->usuario_envia_id,
                ];
                break;
        }
        return $ruta;
    }

    public function obtenerNombreCanal()
    {
        $nombre_canal = null;
        switch ($this->transferencia->estado) {
            case Transferencias::APROBADO:
                $nombre_canal = 'transferencia-saldo-' . $this->transferencia->usuario_envia_id;
                break;
            case Transferencias::RECHAZADO:
                $nombre_canal = 'transferencia-saldo-' . $this->transferencia->usuario_envia_id;
                break;
            case Transferencias::PENDIENTE:
                $nombre_canal = 'transferencia-saldo-' . $this->transferencia->usuario_recibe_id;
                break;
            case Transferencias::ANULADO:
                $nombre_canal = 'transferencia-saldo-' . $this->transferencia->usuario_envia_id;
                break;
        }
        return $nombre_canal;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel($this->nombre_canal);
    }
    public function broadcastAs()
    {
        return 'transferencia-saldo-event';
    }
}
