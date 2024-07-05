<?php

namespace App\Http\Resources\Bodega;

use Illuminate\Http\Resources\Json\JsonResource;

class PermisoArmaResource extends JsonResource
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
            'fecha_emision' => $this->fecha_emision,
            'fecha_caducidad' => $this->fecha_caducidad,
            'imagen_permiso' => $this->imagen_permiso ? url($this->imagen_permiso) : null,
        ];
    }
}
