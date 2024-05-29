<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicacionConsultaResource extends JsonResource
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
            'id' =>$this->id,
            'consulta' =>$this->consulta_id,
            'consulta_info' =>$this->consulta,
            'receta' =>$this->receta_id,
            'receta_info' =>$this->receta,
        ];
    }
}
