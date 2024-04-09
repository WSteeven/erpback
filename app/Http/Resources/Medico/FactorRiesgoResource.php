<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class FactorRiesgoResource extends JsonResource
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
            'tipo_factor_riesgo' => $this->tipo_factor_riesgo_id,
            'tipo_factor_riesgo_info' => $this->tipoFactorRiesgo !== null ? $this->tipoFactorRiesgo?->nombre:' ',
            'categoria_factor_riesgo' => $this->categoria_factor_riesgo_id,
            'categoria_factor_riesgo_info' => $this->categoriaFactorRiesgo !== null ? $this->categoriaFactorRiesgo?->nombre:' ' ,
            'ficha_preocupacional_id' => $this->ficha_preocupacional_id,
        ];
    }
}
