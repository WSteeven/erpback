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
        $detalles = TransferenciaProductoEmpleado::find($this->id)->listadoProductos(); //$this->id);
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
            'cliente' => $this->cliente_id,
            // 'cliente' => $this->sucursal?->cliente?->empresa?->razon_social,
            // 'cliente_id' => $this->sucursal?->cliente_id,
        ];

        if ($controller_method == 'show') {
            $modelo['solicitante'] = $this->solicitante_id;
            $modelo['empleado_origen'] = $this->empleado_origen_id;
            $modelo['empleado_destino'] = $this->empleado_destino_id;
            $modelo['proyecto_origen'] = $this->proyecto_origen_id;
            $modelo['nombre_proyecto_origen'] = $this->proyectoOrigen ? $this->proyectoOrigen->nombre . ' | ' . $this->proyectoOrigen->codigo_proyecto : null;
            $modelo['proyecto_destino'] = $this->proyecto_destino_id;
            $modelo['nombre_proyecto_destino'] = $this->proyectoDestino ? $this->proyectoDestino->nombre . ' | ' . $this->proyectoDestino->codigo_proyecto : null;
            $modelo['etapa_origen'] = $this->etapa_origen_id;
            $modelo['nombre_etapa_origen'] = $this->etapaOrigen?->nombre;
            $modelo['etapa_destino'] = $this->etapa_destino_id;
            $modelo['nombre_etapa_destino'] = $this->etapaDestino?->nombre;
            $modelo['tarea_origen'] = $this->tarea_origen_id;
            $modelo['nombre_tarea_origen'] = $this->tareaOrigen ? $this->tareaOrigen?->codigo_tarea . ' | ' . $this->tareaOrigen?->titulo : null;
            $modelo['tarea_destino'] = $this->tarea_destino_id;
            $modelo['nombre_tarea_destino'] = $this->tareaDestino ? $this->tareaDestino?->codigo_tarea . ' | ' . $this->tareaDestino?->titulo : null;
            $modelo['autorizador'] = $this->autorizador_id;
            $modelo['autorizacion'] = $this->autorizacion_id;
            $modelo['nombre_cliente'] = $this->cliente?->empresa->razon_social;
        }

        return $modelo;
    }

    private function descripcionTarea($tarea)
    {
        return $tarea?->codigo_tarea . ' - ' . $tarea?->titulo;
    }
}
