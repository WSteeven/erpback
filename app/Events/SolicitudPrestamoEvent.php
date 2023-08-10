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
        $ruta = $solicitudPrestamo->estado == 1 ? '/autorizar-solicitudPrestamo' : '/solicitudPrestamo';
        $this->solicitudPrestamo = $solicitudPrestamo;
        $informativa = false;
        switch ($solicitudPrestamo->estado) {
            case 1:
                $mensaje = $this->mostrar_mensaje($solicitudPrestamo);
                break;
            case 2:
                $informativa = true;
                $this->jefeInmediato = Departamento::where('id', 9)->first()->responsable_id;
                $mensaje = 'Te han aprobado un prestamo por un monto de $'.$solicitudPrestamo->monto.' a '. $solicitudPrestamo->plazo.'  meses de plazo' ;
                break;
            case 3:
                $informativa = true;
                $mensaje = 'Te han rechazado un prestamo';
                break;
            case 4:
                $informativa = true;
                $mensaje = 'Te han validado un prestamo con la siguiente sugerencia: '.$solicitudPrestamo->observacion;
                break;
            default:
                $mensaje = 'Tienes un prestamo por aprobar';
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
