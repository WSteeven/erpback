<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ComprobanteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $modelo =  [
            'transaccion_id' => $this->transaccion_id,
            'firmada' => $this->firmada,
            'estado' => $this->estado,
            'observacion' => $this->observacion,
        ];

        return $modelo;
    }
}
