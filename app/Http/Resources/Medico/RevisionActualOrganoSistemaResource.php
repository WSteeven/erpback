<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class RevisionActualOrganoSistemaResource extends JsonResource
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
            'organo_sistema' => $this->organo_sistema_id,
            'organo_sistema_info' => $this->organoSistema !== null ?  $this->organoSistema?->nombre : ' ',
            'descripcion' => $this->descripcion,
            'ficha_preocupacional_id' => $this->ficha_preocupacional_id
        ];
    }
}
