<?php

namespace App\Events;

use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\RecursosHumanos\NominaPrestamos\SolicitudPrestamoEmpresarial;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;
use Throwable;

class SolicitudPrestamoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Notificacion $notificacion;
    public SolicitudPrestamoEmpresarial $solicitudPrestamo;
    public int $jefeInmediato = 0;

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Throwable
     */
    public function __construct($solicitudPrestamo)
    {
        $empleado_id = auth()->user()->empleado->id;
        $responsableRRHH = Departamento::where('id', 7)->first()->responsable_id;
        $responsableGerencia = Departamento::where('id', 9)->first()->responsable_id;
        $originador = $empleado_id;
        $destinatario = $empleado_id;
        $ruta = '/solicitud-prestamo-empresarial';
        $this->solicitudPrestamo = $solicitudPrestamo;
        $informativa = false;
        switch ($solicitudPrestamo->estado) {
            case 1: // PENDIENTE: Empleado realiza solicitud -> Notificación a RRHH
                $this->jefeInmediato = $responsableRRHH;
                $mensaje = $this->mostrar_mensaje($solicitudPrestamo);
                $originador = $solicitudPrestamo->solicitante;
                $destinatario = $responsableRRHH;
                break;
            case 2: // APROBADO: Gerente aprueba solicitud -> Notifica a Empleado
                $informativa = true;
                $mensaje = 'Tu solicitud de préstamo ha sido aprobada por el Gerente. RRHH realizará el registro correspondiente.';
                $this->jefeInmediato = $solicitudPrestamo->solicitante;
                $originador = $responsableGerencia;
                $destinatario = $solicitudPrestamo->solicitante;
                break;

            case 3: // CANCELADO: RRHH o Gerente cancelan la solicitud -> Notifica a Empleado
                $informativa = true;
                $mensaje = 'Tu solicitud de préstamo ha sido CANCELADA por ' . $empleado_id == $responsableGerencia ? 'el Gerente General.' : 'RRHH.';
                $this->jefeInmediato = $solicitudPrestamo->solicitante;
                $destinatario = $solicitudPrestamo->solicitante;
                break;

            case 4: // VALIDADO: RRHH valida -> Notifica a Empleado
                $informativa = true;
                $mensaje = 'Tu solicitud de préstamo ha sido VALIDADA por RRHH. Esperando aprobación del Gerente.';
                $this->jefeInmediato = $solicitudPrestamo->solicitante;
                $originador = $responsableRRHH;
                $destinatario = $solicitudPrestamo->solicitante;
                break;
            default:
                $mensaje = 'Estado de solicitud no reconocido.';
                $this->jefeInmediato = $solicitudPrestamo->solicitante;
                break;
        }

        $this->notificacion = Notificacion::crearNotificacion($mensaje, $ruta, TiposNotificaciones::SOLICITUD_PRESTAMO_EMPRESARIAL, $originador,  $destinatario, $solicitudPrestamo, $informativa);
    }

    public function mostrar_mensaje($prestamo)
    {
        $empleado = Empleado::find($prestamo->solicitante);
        return $empleado->nombres . ' ' . $empleado->apellidos . ' ha solicitado un prestamo por un monto de  $' . $prestamo->monto;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        $nombre_chanel = $this->solicitudPrestamo->estado == 1 ?
            'solicitud-prestamo-empresarial-' . $this->jefeInmediato :
            'solicitud-prestamo-empresarial-' . $this->solicitudPrestamo->solicitante;
        return new Channel($nombre_chanel);
    }


    public function broadcastAs()
    {
        return 'solicitud-prestamo-empresarial-event';
    }
}
