<?php

namespace App\Events\RecursosHumanos;

use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\Notificacion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;
use Throwable;

class NotificarExpiracionPeriodoPruebaRecursosHumanosEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Notificacion $notificacion;
    public string $ruta ='/';
    public Empleado $empleado;
    public Departamento $departamento_rrhh;
    public int $dias_transcurridos;


    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Throwable
     */
    public function __construct(Empleado $empleado, int $dias_transcurridos)
    {
        $this->empleado = $empleado;
        $this->dias_transcurridos = $dias_transcurridos;
        $this->departamento_rrhh = Departamento::where('nombre', Departamento::DEPARTAMENTO_RRHH)->first();
        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->ruta, TiposNotificaciones::DEVOLUCION, null, $this->departamento_rrhh->responsable_id, $this->empleado, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('expiracion-periodo-prueba-rrhh-'.$this->departamento_rrhh->responsable_id);
    }
    public function broadcastAs(){
        return 'expiracion-periodo-prueba-rrhh-event';
    }
    private function obtenerMensaje(){
        return 'RECORDATORIO: El empleado '.Empleado::extraerNombresApellidos($this->empleado).' tiene '.$this->dias_transcurridos.' días laborados, por favor, asegurese de hacer seguimiento al Jefe Inmediato para que realice la evaluación de desempeño al empleado antes de los 90 días';
    }
}
