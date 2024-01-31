<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class FichaAptitudResource extends JsonResource
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
            'fecha_emision' => $this->fecha_emision,
            'observaciones_aptitud_medica' => $this->observaciones_aptitud_medica,
            'recomendaciones' => $this->recomendaciones,
            'tipo_evaluacion_id' => $this->tipo_evaluacion_id,
            'tipo_aptitud_medica_laboral_id' => $this->tipo_aptitud_medica_laboral_id,
            'tipo_evaluacion_medica_retiro_id' => $this->tipo_evaluacion_medica_retiro_id,
            'preocupacional_id' => $this->preocupacional_id
        ];
    }
}
