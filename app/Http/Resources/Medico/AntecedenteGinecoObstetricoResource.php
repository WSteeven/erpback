<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class AntecedenteGinecoObstetricoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            'id' => $this->id,
            'menarquia' => $this->menarquia,
            'ciclos' => $this->ciclos,
            'fecha_ultima_menstruacion' => $this->fecha_ultima_menstruacion,
            'gestas' => $this->gestas,
            'partos' => $this->partos,
            'cesareas' => $this->cesareas,
            'abortos' => $this->abortos,
            'antecedentes_personales' => $this->antecedentes_personales_id,
        ];
    }
}
