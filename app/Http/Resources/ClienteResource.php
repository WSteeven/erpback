<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClienteResource extends JsonResource
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
            'empresa_id' => $this->empresa_id,
            'parroquia_id' => $this->parroquia_id,
            'requiere_bodega' => $this->requiere_bodega,
            'estado' => $this->estado
        ];
    }
}
