<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class ExamenEspecificoResource extends JsonResource
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
            'examen'=> $this->examen,
            'fecha'=> $this->fecha,
            'resultados'=> $this->resultados,
            'preocupacional_id'=> $this->preocupacional_id,
        ];
    }
}
