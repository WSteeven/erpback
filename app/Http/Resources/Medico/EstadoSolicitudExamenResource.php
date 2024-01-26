<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class EstadoSolicitudExamenResource extends JsonResource
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
            'registro_empleado' => $this->registro_id,
            'registro_empleado_info' => $this->registroEmpleadoExamen !== null?$this->registroEmpleadoExamen->nombre:' ',
            'tipo_examen' => $this->tipo_examen_id,
            'tipo_examen_info' => $this->tipoExamen !== null ? $this->tipoExamen?->nombre:' ',
            'estado_examen' => $this->estado_examen_id,
            'estado_examen_info' => $this->estadoExamen !== null ? $this->estadoExamen?->nombre:' ',
        ];
    }
}
