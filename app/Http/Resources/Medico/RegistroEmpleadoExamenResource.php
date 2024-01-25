<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class RegistroEmpleadoExamenResource extends JsonResource
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
            'numero_registro' => $this->nombre,
            'observacion' => $this->observacion,
            'empleado' => $this->empleado_id,
            'empleado_info' => $this->empleado !== null ? $this->empleado?->nombres . ' ' . $this->empleado?->apellidos : ' ',
            'tipo_proceso_examen' => $this->tipo_proceso_examen,
            'estados_solicitudes_examenes' => $this->estadosSolicitudesExamenes !== null ? $this->estadosSolicitudesExamenes:[],
        ];
    }
}
