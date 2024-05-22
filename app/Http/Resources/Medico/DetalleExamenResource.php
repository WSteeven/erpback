<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class DetalleExamenResource extends JsonResource
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
            'tipo_examen' => $this->tipo_examen_id,
            'tipo_examen_info' => $this->tipoExamen !==null ?$this->tipoExamen?->nombre:' ',
            'categoria_examen' => $this->categoria_examen_id,
            'categoria_examen_info' => $this->categoriaExamen !==null ?$this->categoriaExamen?->nombre:' ',
            'examen' => $this->examen_id,
            'examen_info' => $this->examen !==null ?$this->examen?->nombre:' ',

        ];
    }
}
