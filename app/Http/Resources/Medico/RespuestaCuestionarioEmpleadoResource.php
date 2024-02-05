<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class RespuestaCuestionarioEmpleadoResource extends JsonResource
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
            'pregunta' => $this->pregunta_id,
            'pregunta_info' => $this->pregunta?->pregunta,
            'respuesta' => $this->respuesta_id,
            'respuesta_info' => $this->respuesta->respuesta,
            'empleado' => $this->empleado_id,
            'empleado_info' => $this->empleado !== null ?$this->empleado->nombres.' '.$this->empleado->apellidos: '',
        ];
    }
}
