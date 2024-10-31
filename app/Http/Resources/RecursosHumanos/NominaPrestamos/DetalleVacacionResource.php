<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetalleVacacionResource extends JsonResource
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
            'vacacion_id'=>$this->vacacion_id,
            'fecha_inicio'=>$this->fecha_inicio,
            'fecha_fin'=>$this->fecha_fin,
            'dias_utilizados'=>$this->dias_utilizados,
            'vacacionable_id'=>$this->vacacionable_id, //?: 'Sin Ref. ID',
            'vacacionable_type'=>$this->vacacionable_type, //?:'Sin Ref.',
            'observacion'=>$this->observacion,
        ];
    }
}
