<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class ResultadoExamenResource extends JsonResource
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
            'resultado' => $this->resultado,
            // 'fecha_examen' => $this->fecha_examen,
            'configuracion_examen_campo' => $this->configuracion_examen_campo_id,
            'examen_solicitado' => $this->examen_solicitado_id,
            'observaciones' => $this->observaciones,
            // 'configuracion_examen_info' => $this->configuracion_examen !== null ? $this->configuracion_examen?->nombre_prueba :' ',
            // 'empleado' => $this->empleado_id,
            // 'empleado_info' => $this->empleado !== null ? $this->empleado?->nombres . ' ' . $this->empleado?->apellidos : ' ',
        ];
    }
}
