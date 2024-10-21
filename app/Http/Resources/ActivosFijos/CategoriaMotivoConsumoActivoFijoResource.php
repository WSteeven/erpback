<?php

namespace App\Http\Resources\ActivosFijos;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoriaMotivoConsumoActivoFijoResource extends JsonResource
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
        ];
    }
}
