<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class OpcionRespuestaTipoEvaluacionMedicaRetiroResource extends JsonResource
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
            'respuesta' => $this->respuesta,
            'tipo_evaluacion_medica_retiro' => $this->tipo_evaluacion_medica_retiro_id,
        ];
    }
}
