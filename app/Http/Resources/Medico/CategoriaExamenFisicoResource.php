<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoriaExamenFisicoResource extends JsonResource
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
            'region_cuerpo' => $this->region_cuerpo_id,
        ];
    }
}
