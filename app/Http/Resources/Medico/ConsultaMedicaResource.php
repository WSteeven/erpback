<?php

namespace App\Http\Resources\Medico;

use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsultaMedicaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $receta = $this->receta;

        return [
            'id' => $this->id,
            'rp' => $receta?->rp,
            'receta' => $receta,
            'prescripcion' => $receta?->prescripcion,
            'observacion' => $this->observacion,
            'cita_medica_id' => $this->cita_medica_id,
            'tipo_cita_medica' => $this->citaMedica?->tipo_cita_medica,
            'registro_empleado_examen_id' => $this->registro_empleado_examen_id,
            'diagnosticos' => DiagnosticoCitaResource::collection($this->diagnosticosCitaMedica),
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'dado_alta' => $this->dado_alta,
        ];
    }
}
