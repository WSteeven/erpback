<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use Illuminate\Http\Resources\Json\JsonResource;

class HorasExtrasTipoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $modelo = [
            'id' => $this->id,
            'nombre' => $this->nombre,
        ];
        return $modelo;
    }
}
