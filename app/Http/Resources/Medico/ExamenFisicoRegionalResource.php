<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class ExamenFisicoRegionalResource extends JsonResource
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
            'categoria_examen_fisico' => $this->categoria_examen_fisico_id,
            'categoria_examen_fisico_info' => $this->categoriaExamenFisico !== null ? $this->categoriaExamenFisico?->nombre:'',
            'preocupacional_id' => $this->preocupacional_id
        ];
    }
}
