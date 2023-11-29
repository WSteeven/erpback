<?php

namespace App\Http\Resources\ComprasProveedores;

use Illuminate\Http\Resources\Json\JsonResource;

class NovedadOrdenCompraResource extends JsonResource
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
            'ticket' => $this->ticket_id,
        ];
    }
}
