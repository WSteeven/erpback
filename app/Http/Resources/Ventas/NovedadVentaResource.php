<?php

namespace App\Http\Resources\Ventas;

use Illuminate\Http\Resources\Json\JsonResource;

class NovedadVentaResource extends JsonResource
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
            'fecha_hora' => $this->fecha_hora,
            'actividad' => $this->actividad,
            'observacion' => $this->observacion,
            'fotografia' => $this->fotografia ? url($this->fotografia) : null,
            'venta' => $this->venta_id,
        ];
    }
}
