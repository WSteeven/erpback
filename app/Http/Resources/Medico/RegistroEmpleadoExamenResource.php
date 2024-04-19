<?php

namespace App\Http\Resources\Medico;

use Carbon\Carbon;
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
            'numero_registro' => $this->numero_registro,
            'observacion' => $this->observacion,
            'empleado' => $this->empleado_id,
            'empleado_info' => $this->empleado !== null ? $this->empleado?->nombres . ' ' . $this->empleado?->apellidos : ' ',
            'genero' => $this->empleado !== null ? $this->empleado?->genero : ' ',
            'tipo_proceso_examen' => $this->tipo_proceso_examen,
            'estados_solicitudes_examenes' => $this->estadosSolicitudesExamenes !== null ? $this->estadosSolicitudesExamenes:[],
            'created_at' => Carbon::parse($this->created_at)->format('d-m-Y H:i:s'),
            'ficha_aptitud' => $this->fichaAptitud?->id,
            'ficha_periodica' => $this->fichaPeriodica?->id,
            'ficha_retiro' => null, // $this->fichaRetiro?->id,
        ];
    }
}
