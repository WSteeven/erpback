<?php

namespace App\Http\Resources\Medico;

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
            'id'=> $this->id,
            'empleado' => $this->empleado_id,
            'empleado_info' => $this->empleado? $this->empleado->nombres.' '.$this->empleado->apellidos:'',
            'diagnostico_cita' => $this->diagnostico_cita_id,
            'diagnostico_cita_info' => $this->diagnostico? $this->diagnostico->recomendacion:'',
            'cita' => $this->cita_id,
            'cita_info' => $this->cita,
        ];
    }
}
