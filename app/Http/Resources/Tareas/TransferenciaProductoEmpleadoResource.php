<?php

namespace App\Http\Resources\Tareas;

use App\Models\Tareas\TransferenciaProductoEmpleado;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferenciaProductoEmpleadoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $detalles = TransferenciaProductoEmpleado::listadoProductos($this->id);
        $modelo = [
            'id' => $this->id,
            'justificacion' => $this->justificacion,
            'solicitante' => $this->solicitante->nombres . ' ' . $this->solicitante->apellidos,
            'solicitante_id' => $this->solicitante_id,
            'tarea_origen' => $this->descripcionTarea($this->tareaOrigen), //$this->tareaOrigen?->titulo,
            'tarea_destino' => $this->descripcionTarea($this->tareaDestino), //$this->tareaDestino?->titulo,
            'tarea_origen_id' => $this->tarea_origen_id,
            'tarea_destino_id' => $this->tarea_destino_id,
            'estado' => $this->estado,
            'observacion_aut' => $this->observacion_aut,
            'autorizacion' => $this->autorizacion?->nombre,
            'autorizador' => $this->autorizador?->nombres . ' ' . $this->autorizador?->apellidos,
            'autorizador_id' => $this->autorizador_id,
            'listado_productos' => $detalles,
            'created_at' => date('d/m/Y', strtotime($this->created_at)),
            'updated_at' => $this->updated_at,
            'tiene_observacion_aut' => $this->observacion_aut ? true : false,
            'cliente' => $this->sucursal?->cliente?->empresa?->razon_social,
            'cliente_id' => $this->sucursal?->cliente_id,
        ];

        if ($controller_method == 'show') {
            $modelo['solicitante'] = $this->solicitante_id;
            $modelo['empleado_origen'] = $this->empleado_origen_id;
            $modelo['empleado_destino'] = $this->empleado_destino_id;
            $modelo['tarea_origen'] = $this->tarea_origen_id;
            $modelo['tarea_destino'] = $this->tarea_destino_id;
            $modelo['autorizador'] = $this->autorizador_id;
            $modelo['autorizacion'] = $this->autorizacion_id;
        }

        return $modelo;
    }

    private function descripcionTarea($tarea)
    {
        return $tarea->codigo_tarea . ' - ' . $tarea->titulo;
    }
}
