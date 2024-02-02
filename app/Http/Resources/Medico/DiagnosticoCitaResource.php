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
            'recomendacion'=> $this->recomendacion,
            'cie'=> $this->cie_id,
            'cie_info'=> $this->cie !== null ? $this->cie->codigo.'-'.$this->cie->nombre_enfermedad:' ',
        ];
    }
}
