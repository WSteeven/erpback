<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class EsquemaVacunaResource extends JsonResource
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
            'nombre_vacuna' => $this->nombre_vacuna,
            'dosis_totales' => $this->dosis_totales,
            'dosis_aplicadas' => $this->dosis_aplicadas,
            'observacion' => $this->observacion,
            'registro_empleado_examen' => $this->registro_empleado_examen_id,
            'registro_empleado_examen_info' =>  $this->registroEmpleadoExamen !== null ?$this->registroEmpleadoExamen?->numero_registro:'',
            'tipo_vacuna' => $this->tipo_vacuna_id,
            'tipo_vacuna_info' =>  $this->tipoVacuna !== null ?$this->tipoVacuna?->nombre:'',
        ];
    }
}
