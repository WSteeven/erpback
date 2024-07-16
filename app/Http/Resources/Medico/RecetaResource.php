<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class RecetaResource extends JsonResource
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
            'rp' => $this->rp,
            'prescripcion' => $this->prescripcion,
            'cita_medica' => $this->cita_medica_id,
            'registro_empleado_examen' => $this->registro_empleado_examen_id,
            'diagnosticos' => $this->diagnosticos,
        ];
    }
}
