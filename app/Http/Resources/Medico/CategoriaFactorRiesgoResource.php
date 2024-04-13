<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoriaFactorRiesgoResource extends JsonResource
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
            'nombre' => $this->nombre,
            'tipo_factor_riesgo' => $this->tipo_factor_riesgo_id,
        ];
    }
}
