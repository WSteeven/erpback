<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class AntecedenteFamiliarResource extends JsonResource
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
            'id'=>$this->id,
            'tipo_antecedente_familiares' => $this->tipo_antecedente_familiar_id,
            'tipo_antecedente_familiares_info' => $this->tipoAntecedenteFamiliar !== null? $this->tipoAntecedenteFamiliar->nombre:' ',
            'preocupacional_id'=> $this->preocupacional_id,
        ];
    }
}
