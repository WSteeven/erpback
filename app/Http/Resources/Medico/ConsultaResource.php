<?php

namespace App\Http\Resources\Medico;

use App\Models\Empleado;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsultaResource extends JsonResource
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
            'id'=> $this->cita_id,
            'empleado_id' => $this->empleado_id,
            'empleado' => Empleado::extraerNombresApellidos($this->empleado),
            'cita_id' => $this->cita_id,
            'cita' => $this->cita,
            'diagnosticos' => $this->diagnosticosCita,
        ];
    }
}
