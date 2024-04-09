<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class ActividadFisicaResource extends JsonResource
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
            'nombre_actividad' => $this->nombre_actividad,
            'tiempo' => $this->tiempo,
            'ficha_preocupacional' => $this->ficha_preocupacional_id
        ];
    }
}
