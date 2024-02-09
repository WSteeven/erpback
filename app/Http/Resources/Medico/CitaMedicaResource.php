<?php

namespace App\Http\Resources\Medico;

use App\Models\Empleado;
use Carbon\Carbon;
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
            'created_at' => Carbon::parse($this->created_at)->format('d-m-Y H:i:s'),
            'estado_cita_medica' => $this->estado_cita_medica,
            // 'estado_cita_medica_info' => $this->estadoCitaMedica !== null ? $this->estadoCitaMedica?->nombre : ' ',
            'paciente_id' => $this->paciente_id,
            'paciente' => Empleado::extraerNombresApellidos($this->paciente),
        ];
    }
}
