<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CantonResource extends JsonResource
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
            'canton' => $this->canton,
            'cod_canton' => $this->cod_canton,
            'provincia_id' => $this->provincia_id,
            'provincia' => $this->provincia->provincia,
        ];
    }
}
