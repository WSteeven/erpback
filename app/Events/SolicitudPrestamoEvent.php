<?php

namespace App\Events;

use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\RecursosHumanos\NominaPrestamos\SolicitudPrestamoEmpresarial;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class SolicitudPrestamoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public Notificacion $notificacion;
    public SolicitudPrestamoEmpresarial $solicitudPrestamo;
    public  $jefeInmediato = 0;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($solicitudPrestamo)
    {
        $this->jefeInmediato = Departamento::where('id', 7)->first()->responsable_id;
        $ruta =  '/solicitud-prestamo-empresarial';
        $this->solicitudPrestamo = $solicitudPrestamo;
        $informativa = false;
        switch ($solicitudPrestamo->estado) {
            case 1: // Empleado realiza solicitud -> Notificación a RRHH
                $this->jefeInmediato = Departamento::where('id', 7)->first()->responsable_id;
                $mensaje = $this->mostrar_mensaje($solicitudPrestamo);
                break;

            case 2: // RRHH cancela solicitud -> Notifica a Empleado
                $informativa = true;
                $mensaje = 'Tu solicitud de préstamo ha sido cancelada por RRHH.';
                $this->jefeInmediato = $solicitudPrestamo->solicitante;
                break;

            case 3: // RRHH valida solicitud -> Notifica a Empleado y a Gerente
                $informativa = true;
                $mensaje = 'Tu solicitud de préstamo ha sido validada por RRHH. Esperando aprobación del Gerente.';
                $this->jefeInmediato = Departamento::where('id', 9)->first()->responsable_id;
                break;

            case 4: // Gerente cancela -> Notifica a Empleado
                $informativa = true;
                $mensaje = 'Tu solicitud de préstamo ha sido cancelada por el Gerente.';
                $this->jefeInmediato = $solicitudPrestamo->solicitante;
                break;

            case 5: // Gerente aprueba -> Notifica a Empleado y RRHH
                $informativa = true;
                $mensaje = 'Tu solicitud de préstamo ha sido aprobada por el Gerente. RRHH realizará el registro correspondiente.';
                $this->jefeInmediato = Departamento::where('id', 7)->first()->responsable_id;
                break;

            default:
                $mensaje = 'Estado de solicitud no reconocido.';
                break;
        }
        $destinatario = $solicitudPrestamo->estado != 1 ?  $this->jefeInmediato : $solicitudPrestamo->solicitante;
        $remitente = $solicitudPrestamo->estado != 1 ? $solicitudPrestamo->solicitante : $this->jefeInmediato;
        $this->notificacion = Notificacion::crearNotificacion($mensaje, $ruta, TiposNotificaciones::SOLICITUD_PRESTAMO_EMPRESARIAL, $destinatario, $remitente, $solicitudPrestamo, $informativa);
    }
    public function mostrar_mensaje($prestamo)
    {

        $empleado = Empleado::find($prestamo->solicitante);
        $mensaje = $empleado->nombres . ' ' . $empleado->apellidos . ' ha solicitado un prestamo por un monto de  $' . $prestamo->monto;
        return $mensaje;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $nombre_chanel =  $this->solicitudPrestamo->estado == 1 ? 'solicitud-prestamo-empresarial-' . $this->jefeInmediato : 'solicitud-prestamo-empresarial-' . $this->solicitudPrestamo->solicitante;
        return new Channel($nombre_chanel);
    }


    public function broadcastAs()
    {
        return 'solicitud-prestamo-empresarial-event';
    }
}
