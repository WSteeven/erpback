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
            'tipo_habito_toxico' => $this->tipoHabitoToxico->nombre,
            'tipo_habito_toxico_id' => $this->tipo_habito_toxico_id,
            'tiempo_consumo_meses'=>$this->tiempo_consumo_meses,
            'cantidad'=>$this->cantidad,
            'ex_consumidor'=>$this->ex_consumidor,
            'tiempo_abstinencia_meses'=>$this->tiempo_abstinencia_meses,
        ];
    }
}
