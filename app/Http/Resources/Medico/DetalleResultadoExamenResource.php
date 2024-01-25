<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class DetalleResultadoExamenResource extends JsonResource
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
            'tipo_examen' => $this->tipo_examen_id,
            'tipo_examen_info' => $this->tipoExamen !== null ? $this->tipoExamen?->nombre : ' ',
            'examen' => $this->examen_id,
            'examen_info' => $this->examen !== null ? $this->examen?->nombre : ' ',
        ];
    }
}
