<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class CitaMedicaResource extends JsonResource
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
            'sintomas' => $this->sintomas,
            'razon' => $this->razon,
            'observacion' => $this->observacion,
            'fecha_hora_cita' => $this->fecha_hora_cita,
            'estado_cita_medica' => $this->estado_cita_medica_id,
            'estado_cita_medica_info' => $this->estadoCitaMedica !== null? $this->estadoCitaMedica?->nombre:' ',
            'paciente' => $this->paciente_id,
            'paciente_info' => $this->paciente !== null? $this->paciente->nombres.' '.$this->paciente->apellidos:' ',
        ];
    }
}
