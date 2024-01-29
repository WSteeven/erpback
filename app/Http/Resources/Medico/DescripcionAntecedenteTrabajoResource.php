<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class DescripcionAntecedenteTrabajoResource extends JsonResource
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
            'calificado_iess' => $this->calificado_iess,
            'descripcion' => $this->descripcion,
            'fecha' => $this->fecha,
            'observacion' => $this->observacion,
            'tipo_descripcion_antecedente_trabajo' => $this->tipo_descripcion_antecedente_trabajo,
            'preocupacional_id' => $this->preocupacional_id
        ];
    }
}
