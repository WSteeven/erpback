<?php

namespace App\Http\Resources\RecursosHumanos\ControlPersonal;

use Illuminate\Http\Resources\Json\JsonResource;

class JustificacionesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
