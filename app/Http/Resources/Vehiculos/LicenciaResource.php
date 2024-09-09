<?php

namespace App\Http\Resources\Vehiculos;

use Illuminate\Http\Resources\Json\JsonResource;

class LicenciaResource extends JsonResource
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
            'tipo_licencia' => $this->tipo_licencia,
            'inicio_vigencia' => $this->inicio_vigencia,
            'fin_vigencia' => $this->fin_vigencia,
            'conductor_id' => $this->conductor_id,
        ];
    }
}
