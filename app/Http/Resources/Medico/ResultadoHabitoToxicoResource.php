<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class ResultadoHabitoToxicoResource extends JsonResource
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
            'tipo_habito_toxico' => $this->tipo_habito_toxico_id,
            'tipo_habito_toxico_info' => $this->tipoHabitoToxico !== null ? $this->tipoHabitoToxico?->nombre:'',
            'tiempo_consumo' => $this->tiempo_consumo,
        ];
    }
}
