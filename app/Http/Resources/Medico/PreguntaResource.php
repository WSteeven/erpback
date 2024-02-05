<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class PreguntaResource extends JsonResource
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
            'codigo' => $this->codigo,
            'pregunta' => $this->pregunta,
            'respuesta' => count($this->respuestaCuestionarioEmpleado) > 0 ? $this->respuestaCuestionarioEmpleado?->respuesta?->id : null,
            'posibles_respuestas' => count($this->cuestionario) > 0  ? $this->cuestionario?->respuesta : []
        ];
    }
}
