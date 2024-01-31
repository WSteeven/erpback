<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfesionalSaludResource extends JsonResource
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
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'codigo' => $this->codigo,
            'ficha_aptitud_id' => $this->ficha_aptitud_id,
        ];
    }
}
