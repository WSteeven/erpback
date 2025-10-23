<?php

namespace App\Http\Resources\Conecel\GestionTareas;

use Illuminate\Http\Resources\Json\JsonResource;

class TipoActividadResource extends JsonResource
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
