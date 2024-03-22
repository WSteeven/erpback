<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class DiagnosticoCitaResource extends JsonResource
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
            'recomendacion' => $this->recomendacion,
            'cie' => $this->cie_id,
            'codigo' => $this->cie->codigo,
            'nombre_enfermedad' => $this->cie->nombre_enfermedad,
        ];
    }
}
