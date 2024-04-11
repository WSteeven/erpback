<?php

namespace App\Http\Resources\RecursosHumanos;

use Illuminate\Http\Resources\Json\JsonResource;

class EmpleadoTipoDiscapacidadPorcentajeResource extends JsonResource
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
