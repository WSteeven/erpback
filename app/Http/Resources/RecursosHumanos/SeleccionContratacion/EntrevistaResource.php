<?php

namespace App\Http\Resources\RecursosHumanos\SeleccionContratacion;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntrevistaResource extends JsonResource
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
            'postulacion_id'=>$this->postulacion_id,
            'fecha_hora'=>$this->fecha_hora,
            'duracion'=>$this->duracion,
            'reagendada'=>$this->reagendada,
            'nueva_fecha_hora'=>$this->nueva_fecha_hora,
            'observacion'=>$this->observacion,
            'asistio' =>$this->asistio
        ];
    }
}
