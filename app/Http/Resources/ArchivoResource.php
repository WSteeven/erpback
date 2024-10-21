<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArchivoResource extends JsonResource
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
            'archivable_id' => $this->archivable_id,
            'archivable_type' => $this->archivable_type,
            'created_at' => $this->created_at,
            'nombre' => $this->nombre,
            'ruta' => $this->ruta ? url($this->ruta) : null,
            'tamanio_bytes' => $this->tamanio_bytes,
            'tipo' => $this->tipo,
            'updated_at' => $this->tipo,
        ];
    }
}
