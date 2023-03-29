<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmergenciaResource extends JsonResource
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
            'trabajo_realizado' => $this->trabajo_realizado,
            'observaciones' => $this->observaciones,
            'materiales_ocupados' => $this->materiales_ocupados,
            'materiales_devolucion' => $this->materiales_devolucion,
            'subtarea' => $this->subtarea_id,
        ];
    }
}
