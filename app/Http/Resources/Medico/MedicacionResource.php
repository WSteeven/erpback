<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicacionResource extends JsonResource
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
            'nombre'=>$this->nombre,
            'cantidad'=>$this->cantidad,
            'ficha_preocupacional_id'=>$this->ficha_preocupacional_id
        ];
    }
}
