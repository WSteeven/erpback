<?php

namespace App\Http\Resources\Medico;

use App\Models\Medico\Examen;
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
            'registro_empleado_examen' => $this->registro_empleado_examen_id,
            // 'examen' => $this->examen_id ? Examen::find($this->examen_id)->nombre : null,
            'examen' => $this->examen_id,
            'tipo_examen' => $this->examen?->tipoExamen?->first()->nombre,
            'categoria' => $this->examen_id ? Examen::find($this->examen_id)->categoria?->first()?->nombre : null,
            'estado_examen_id' => $this->estado_examen_id,
            'estado_examen' => $this->estadoExamen?->nombre,
            'laboratorio_clinico' => $this->laboratorio_clinico_id,
            'fecha_hora_asistencia' => $this->fecha_hora_asistencia,
        ];
    }
}
