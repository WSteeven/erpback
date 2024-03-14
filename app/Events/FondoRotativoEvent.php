<?php

namespace App\Events;

use App\Http\Resources\FondosRotativos\Gastos\GastoResource;
use App\Models\Empleado;
use App\Models\FondosRotativos\Gasto\Gasto;

use App\Models\Notificacion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Src\Config\TiposNotificaciones;

class FondoRotativoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Gasto $gasto;
    public Notificacion $notificacion;
    private String $nombre_canal;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Gasto $gasto)
    {
        $this->gasto = $gasto;
        $ruta = $this->obtenerRuta();
        $this->nombre_canal = $this->obtenerNombreCanal();
        $this->notificacion = Notificacion::crearNotificacion(
            $ruta['mensaje'],
            $ruta['ruta'],
            TiposNotificaciones::AUTORIZACION_GASTO,
            $ruta['originador'],
            $ruta['destinatario'],
            $gasto,
            $ruta['informativa']
        );
        Log::channel('testing')->info('Log', ['notificacion' => $this->notificacion]);

    }

    /**
     * La función `obtenerRuta` determina la ruta y el mensaje en función del estado de un objeto Gasto.
     *
     * @return Un array que contiene información sobre la ruta según el estado del gasto (Gasto). El
     * conjunto incluye la ruta de la ruta, ya sea informativa o no, un mensaje relacionado con el estado
     * del gasto, el originador y el destinatario del mensaje.
     */
    public function obtenerRuta()
    {
        $ruta = null;
        switch ($this->gasto->estado) {
            case Gasto::APROBADO:
                $ruta = [
                    'ruta' => '/gasto',
                    'informativa' => true,
                    'mensaje' => 'Te han aprobado un gasto',
                    'originador' =>  $this->gasto->aut_especial,
                    'destinatario' => $this->gasto->id_usuario,
                ];
                break;
            case Gasto::RECHAZADO:
                $ruta = [
                    'ruta' => '/gasto',
                    'informativa' => true,
                    'mensaje' => 'Te han rechazado un gasto por el siguiente motivo: ' . $this->gasto->detalle_estado,
                    'originador' =>  $this->gasto->aut_especial,
                    'destinatario' => $this->gasto->id_usuario,
                ];
                break;
            case Gasto::PENDIENTE:
                $ruta = [
                    'ruta' => '/autorizar-gasto',
                    'informativa' => false,
                    'mensaje' => $this->mostrarMensaje($this->gasto),
                    'originador' => $this->gasto->id_usuario,
                    'destinatario' => $this->gasto->aut_especial,
                ];
                break;
        }
        return $ruta;
    }
    /**
     * La función "mostrarMensaje" genera un mensaje sobre un gasto específico solicitado por un
     * empleado, incluyendo detalles como el nombre del empleado, el monto del gasto y la descripción.
     *
     * @param Gasto gasto La función `mostrarMensaje` toma como parámetro un objeto `Gasto`. Recupera
     * información relacionada con el objeto `Gasto`, como el empleado que presentó el gasto
     * (`), los detalles del gasto (`) y subdetalles adicionales (`
     *
     * @return La función `mostrarMensaje` devuelve un mensaje que incluye el nombre del empleado, el
     * monto total del gasto, una descripción del detalle del gasto e información adicional de
     * subdetalle. El mensaje se construye concatenando estas piezas de información.
     */
    public function mostrarMensaje()
    {
        $empleado = Empleado::find($this->gasto->id_usuario);
        $modelo = new GastoResource($this->gasto);
        $detalle = $modelo->detalle_info->descripcion;
        $sub_detalle_info = $this->subdetalleInfo($modelo->sub_detalle_info);
        $mensaje = $empleado->nombres . ' ' . $empleado->apellidos . ' ha solicitado un gasto por un monto de $' . $this->gasto->total . ' con respecto a ' . $detalle . ' ' . $sub_detalle_info;
        return $mensaje;
    }
    /**
     * La función `subdetalleInfo` concatena los valores `descripcion` de objetos en un array con comas
     * entremedio.
     *
     * @param array subdetalle_info Parece que la función `subdetalleInfo` tiene como objetivo
     * concatenar la propiedad `descripcion` de cada objeto en la matriz ``, separada
     * por comas.
     *
     * @return La función `subdetalleInfo` devuelve una cadena concatenada de descripciones de la matriz
     * `subdetalle_info`, separadas por comas.
     */
    private function subdetalleInfo($subdetalle_info)
    {
        $descripcion = '';
        $i = 0;
        foreach ($subdetalle_info as $sub_detalle) {
            $descripcion .= $sub_detalle->descripcion;
            $i++;
            if ($i !== count($subdetalle_info)) {
                $descripcion .= ', ';
            }
        }
        return $descripcion;
    }

    public function obtenerNombreCanal()
    {
        $nombre_canal = null;
        switch ($this->gasto->estado) {
            case Gasto::APROBADO:
                $nombre_canal = 'fondo-rotativo-' . $this->gasto->id_usuario;
                break;
            case Gasto::RECHAZADO:
                $nombre_canal = 'fondo-rotativo-' . $this->gasto->id_usuario;
                break;
            case Gasto::PENDIENTE:
                $nombre_canal = 'fondo-rotativo-' . $this->gasto->aut_especial;
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
        return 'fondo-rotativo-event';
    }
}
