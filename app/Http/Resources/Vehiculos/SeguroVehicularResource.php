<?php

namespace App\Http\Resources\Vehiculos;

use Illuminate\Http\Resources\Json\JsonResource;

class SeguroVehicularResource extends JsonResource
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
            'nombre' => $this->nombre,
            'num_poliza' => $this->num_poliza,
            'fecha_caducidad' => date('d-m-Y', strtotime($this->fecha_caducidad)),
            'estado' => $this->estado,
        ];
    }
}
