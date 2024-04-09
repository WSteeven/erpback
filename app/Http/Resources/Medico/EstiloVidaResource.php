<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class EstiloVidaResource extends JsonResource
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
            'actividades_fisicas' => $this->actividades_fisicas,
            'tiempo' => $this->tiempo,
            'ficha_preocupacional' => $this->ficha_preocupacional_id
        ];
    }
}
