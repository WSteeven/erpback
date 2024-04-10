<?php

namespace App\Events;

use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\FondosRotativos\Saldo\Transferencias;
use App\Models\Notificacion;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Src\Config\TiposNotificaciones;

class TransferenciaSaldoContabilidadEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public Transferencias $transferencia;
    public Notificacion $notificacion;
    public $ruta = '/transferencia';
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($transferencia)
    {
        $this->transferencia = $transferencia;
        $this->enviarNotificacionesContabilidad();
    }
    public function obtenerRuta(Empleado $empleado)
    {
        $ruta = null;
        if ($this->transferencia->es_devolucion) {
            switch ($this->transferencia->estado) {
                case  Transferencias::APROBADO:
                    $ruta = [
                        'ruta' => $this->ruta,
                        'informativa' => false,
                        'mensaje' => 'Devolución Aceptada',
                        'originador' =>  $this->transferencia->usuario_recibe_id,
                        'destinatario' =>  $empleado->id,
                    ];
                    break;
                case Transferencias::RECHAZADO:
                    $ruta = [
                        'ruta' => $this->ruta,
                        'informativa' => false,
                        'mensaje' => 'Han rechazado  devolucion  a ' . $this->transferencia->empleadoRecibe->nombres . ' ' . $this->transferencia->empleadoRecibe->apellidos . ' por un monto de $' . $this->transferencia->monto,
                        'originador' =>  $this->transferencia->usuario_recibe_id,
                        'destinatario' =>  $empleado->id,
                    ];
                    break;
                case Transferencias::PENDIENTE:
                    $ruta = [
                        'ruta' => $this->ruta,
                        'informativa' => false,
                        'mensaje' => 'Han realizado una  devolucion  por un monto de $' . $this->transferencia->monto,
                        'originador' =>  $this->transferencia->usuario_recibe_id,
                        'destinatario' =>  $empleado->id,
                    ];
                    break;
                case Transferencias::ANULADO:
                    $ruta = [
                        'ruta' => $this->ruta,
                        'informativa' => false,
                        'mensaje' => 'Han anulado una  devolucion a  ' . $this->transferencia->empleadoEnvia->nombres . ' ' . $this->transferencia->empleadoEnvia->apellidos . ' a ' . $this->transferencia->empleadoRecibe->nombres . ' ' . $this->transferencia->empleadoRecibe->apellidos . ' por un monto de $' . $this->transferencia->monto,
                        'originador' =>  $this->transferencia->usuario_recibe_id,
                        'destinatario' => $empleado->id,
                    ];
                    break;
            }
        } else {
            switch ($this->transferencia->estado) {
                case Transferencias::APROBADO:
                    $ruta = [
                        'ruta' => $this->ruta,
                        'informativa' => false,
                        'mensaje' =>  $this->transferencia->empleadoEnvia->nombres . ' ' . $this->transferencia->empleadoEnvia->apellidos . 'Acepto Transferencia',
                        'originador' =>  $this->transferencia->usuario_recibe_id,
                        'destinatario' => $empleado->id,
                    ];
                    break;
                case Transferencias::RECHAZADO:
                    $ruta = [
                        'ruta' => $this->ruta,
                        'informativa' => false,
                        'mensaje' => 'Han rechazado  transferencia de  ' . $this->transferencia->empleadoEnvia->nombres . ' ' .  $this->transferencia->empleadoEnvia->apellidos . ' a ' . $this->transferencia->empleadoRecibe->nombres . ' ' . $this->transferencia->empleadoRecibe->apellidos . ' por un monto de $' . $this->transferencia->monto,
                        'originador' =>  $this->transferencia->usuario_recibe_id,
                        'destinatario' =>  $empleado->id,
                    ];
                    break;
                case Transferencias::PENDIENTE:
                    $ruta = [
                        'ruta' => $this->ruta,
                        'informativa' => false,
                        'mensaje' => 'Han realizado una  transferencia de  ' .  $this->transferencia->empleadoEnvia->nombres . ' ' .  $this->transferencia->empleadoEnvia->apellidos . ' a ' . $this->transferencia->empleadoRecibe->nombres . ' ' . $this->transferencia->empleadoRecibe->apellidos . ' por un monto de $' . $this->transferencia->monto,
                        'originador'   =>  $this->transferencia->usuario_recibe_id,
                        'destinatario' => $empleado->id,
                    ];
                    break;
                case Transferencias::ANULADO:
                    $ruta = [
                        'ruta' => $this->ruta,
                        'informativa' => false,
                        'mensaje' => 'Han anulado una  transferencia de  ' . $this->transferencia->empleadoEnvia->nombres . ' ' . $this->transferencia->empleadoEnvia->apellidos . ' a ' . $this->transferencia->empleadoRecibe->nombres . ' ' . $this->transferencia->empleadoRecibe->apellidos . ' por un monto de $' . $this->transferencia->monto,
                        'originador' =>  $this->transferencia->usuario_recibe_id,
                        'destinatario' => $empleado->id,
                    ];
                    break;
            }
        }
        return $ruta;
    }
    public function enviarNotificacionesContabilidad()
    {
        $usuarios_contabilidad = User::role(User::ROL_CONTABILIDAD)->where('users.id', '!=', Auth::user()->id)->orderby('users.name', 'asc')->get();
        foreach ($usuarios_contabilidad as $usuario) {
            $ruta =  $this->obtenerRuta($usuario->empleado);
            if ($usuario->empleado != null) {
                $this->notificar(
                    $ruta['mensaje'],
                    $ruta['ruta'],
                    $ruta['originador'],
                    $ruta['destinatario'],
                );
            }
        }
    }
    public function notificar($mensaje, $ruta, $originador, $destinatario)
    {
        $this->notificacion = Notificacion::crearNotificacion(
            $mensaje,
            $ruta,
            TiposNotificaciones::TRANSFERENCIA_SALDO,
            $originador,
            $destinatario,
            $this->transferencia,
            false
        );
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('transferencia-saldo-contabilidad-' . Departamento::DEPARTAMENTO_CONTABILIDAD);
    }
    public function broadcastAs()
    {
        return 'transferencia-saldo-contabilidad-event';
    }
}
