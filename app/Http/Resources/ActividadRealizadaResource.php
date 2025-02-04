<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActividadRealizadaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'fecha_hora'=>$this->fecha_hora,
            'actividad_realizada'=>$this->actividad_realizada,
            'observacion'=>$this->observacion,
            'fotografia'=>$this->fotografia,
            'empleado_id'=>$this->empleado_id,
//            'actividable_id'=>$this->actividable_id,
//            'actividable_type'=>$this->actividable_type,
            'tarea_id'=>$this->tarea_id,
            'tarea'=>$this->tarea_id,
            'kilometraje'=>$this->kilometraje,
        ];
    }
}
