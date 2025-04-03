<?php

namespace App\Events;

use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\RecursosHumanos\NominaPrestamos\SolicitudPrestamoEmpresarial;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;
use Throwable;

class SolicitudPrestamoGerenciaEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public Notificacion $notificacion;
    public SolicitudPrestamoEmpresarial $solicitudPrestamo;
    public  int $jefeInmediato = 0;

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Throwable
     */
    public function __construct($solicitudPrestamo)
    {
        $responsableGerencia = Departamento::where('id', 9)->first()->responsable_id;
        $this->jefeInmediato = $responsableGerencia;
        $ruta =  '/solicitud-prestamo-empresarial';
        $this->solicitudPrestamo = $solicitudPrestamo;
        $this->notificacion = Notificacion::crearNotificacion($this->mostrar_mensaje($solicitudPrestamo), $ruta, TiposNotificaciones::SOLICITUD_PRESTAMO_EMPRESARIAL, auth()->user()->empleado->id, $responsableGerencia, $solicitudPrestamo,true);
    }
    public function mostrar_mensaje($prestamo)
    {
        $empleado = Empleado::find($prestamo->solicitante);
        return ' Se ha solicitado la aprobación de un préstamo por un monto de $'.$prestamo->monto.' a '. $prestamo->plazo.'  meses de plazo con la siguiente sugerencia: '.$prestamo->observacion.' para '. $empleado->nombres . ' ' . $empleado->apellidos;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('solicitud-prestamo-empresarial-gerencia-' . $this->jefeInmediato);
    }


    public function broadcastAs()
    {
        return 'solicitud-prestamo-empresarial-gerencia-event';
    }
}
