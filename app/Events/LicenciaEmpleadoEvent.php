<?php

namespace App\Events;

use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\RecursosHumanos\NominaPrestamos\LicenciaEmpleado;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;
use Throwable;

class LicenciaEmpleadoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public LicenciaEmpleado $licenciaEmpleado;
    public Notificacion $notificacion;
    private int $id_wellington = 117;
    private int $id_veronica_valencia = 155;
    public  int $jefeInmediato=0;

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Throwable
     */
    public function __construct($licenciaEmpleado)
    {
        $ruta = 'licencia-empleado';
        $this->licenciaEmpleado = $licenciaEmpleado;
        $informativa = false;
        switch ($licenciaEmpleado->estado) {
            case 1:
                $mensaje = $this->mostrar_mensaje($licenciaEmpleado);
                break;
            case 2:
                $informativa = true;
               $mensaje = 'Te han aprobado una licencia';
                break;
            default:
            $mensaje = 'Tienes una Licencia por aprobar';
                break;
        }
        $this->jefeInmediato =  Empleado::find($licenciaEmpleado->empleado)->jefe_id;
        if($this->jefeInmediato == $this->id_wellington) $this->jefeInmediato = $this->id_veronica_valencia;
        $destinatario = $licenciaEmpleado->estado!=1?  $this->jefeInmediato:$licenciaEmpleado->empleado;
        $remitente = $licenciaEmpleado->estado!=1? $licenciaEmpleado->empleado: $this->jefeInmediato;
      $this->notificacion = Notificacion::crearNotificacion($mensaje,$ruta, TiposNotificaciones::LICENCIA_EMPLEADO, $destinatario, $remitente,$licenciaEmpleado,$informativa);
}
    public function mostrar_mensaje($licenciaempleado)
    {
        $empleado = Empleado::find($licenciaempleado->empleado);
        return $empleado->nombres.' '.$empleado->apellidos.' ha solicitado un licencia por el siguiente motivo: '.$licenciaempleado->justificacion;
    }

   /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
    */
    public function broadcastOn()
    {
        $nombre_chanel =  $this->licenciaEmpleado->estado==1? 'licencia-empleado-'. $this->jefeInmediato:'licencia-empleado-'. $this->licenciaEmpleado->empleado_id;
        return new Channel($nombre_chanel );
    }


    public function broadcastAs()
    {
        return 'licencia-empleado-event';
    }
}
