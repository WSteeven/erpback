<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActividadRealizadaSeguimientoSubtareaResource extends JsonResource
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
            'fecha_hora' => $this->fecha_hora,
            'trabajo_realizado' => $this->trabajo_realizado,
            'fotografia' => $this->fotografia ? url($this->fotografia) : null,
            'seguimiento' => $this->seguimiento_id,
        ];
    }
}
