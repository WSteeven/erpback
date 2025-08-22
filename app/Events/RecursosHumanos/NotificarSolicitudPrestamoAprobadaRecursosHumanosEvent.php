<?php

namespace App\Events\RecursosHumanos;

use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\RecursosHumanos\NominaPrestamos\SolicitudPrestamoEmpresarial;
use Auth;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;
use Throwable;

class NotificarSolicitudPrestamoAprobadaRecursosHumanosEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Notificacion $notificacion;
    private SolicitudPrestamoEmpresarial $solicitud;
    private Departamento $departamentoRRHH;

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Throwable
     */
    public function __construct(SolicitudPrestamoEmpresarial $solicitud)
    {
        $this->solicitud = $solicitud;
        $this->departamentoRRHH = Departamento::where('nombre', Departamento::DEPARTAMENTO_RRHH)->first();
        $ruta = '/solicitud-prestamo-empresarial';
        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $ruta, TiposNotificaciones::SOLICITUD_PRESTAMO_EMPRESARIAL, Auth::user()->empleado->id, $this->departamentoRRHH->responsable_id, $this->solicitud, false);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('solicitud-prestamo-empresarial-'.$this->departamentoRRHH->responsable_id);
    }

    public function broadcastAs()
    {
        return 'solicitud-prestamo-empresarial-event';
    }


    private function obtenerMensaje(){
        $nombreGerente = Empleado::extraerNombresApellidos(Auth::user()->empleado);
        $nombreEmpleado = Empleado::extraerNombresApellidos($this->solicitud->empleado_info);
        return "El gerente $nombreGerente ha aprobado la solicitud de préstamo empresarial del empleado $nombreEmpleado por un monto de {$this->solicitud->monto} en un plazo de {$this->solicitud->plazo} meses. Por favor registra el respectivo préstamo empresarial";
    }
}
