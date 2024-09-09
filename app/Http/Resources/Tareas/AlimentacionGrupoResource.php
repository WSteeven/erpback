<?php

namespace App\Http\Resources\Tareas;

use App\Models\Empleado;
use App\Models\Tareas\AlimentacionGrupo;
use Illuminate\Http\Resources\Json\JsonResource;

class AlimentacionGrupoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'observacion' => $this->observacion,
            'cantidad_personas' => $this->cantidad_personas,
            'precio' => $this->precio,
            'fecha' => $this->fecha,
            'tarea_id' => $this->tarea_id,
            'tarea' => $this->tarea->codigo_tarea,
            'coordinador' => Empleado::extraerNombresApellidos($this->tarea->coordinador),
            'grupo' => $this->grupo->nombre,
            'total' => $this->cantidad_personas * AlimentacionGrupo::PRECIO_ALIMENTACION,
            'grupo_id' => $this->grupo_id,
            'tipo_alimentacion_id' => $this->tipo_alimentacion_id,
            'tipo_alimentacion' => $this->tipoAlimentacion->descripcion,
        ];
    }
}
