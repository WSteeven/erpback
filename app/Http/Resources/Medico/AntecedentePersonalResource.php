<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class AntecedentePersonalResource extends JsonResource
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
            'antecedentes_quirorgicos'=> $this->antecedentes_quirorgicos,
            'vida_sexual_activa'=> $this->vida_sexual_activa,
            'tiene_metodo_planificacion_familiar'=> $this->tiene_metodo_planificacion_familiar,
            'tipo_metodo_planificacion_familiar'=> $this->tipo_metodo_planificacion_familiar,
            'hijos_vivos'=> $this->hijos_vivos,
            'hijos_muertos'=> $this->hijos_muertos,
            'preocupacional'=> $this->preocupacional_id,
            'preocupacional_info'=> $this->preocupacional !==null ? $this->preocupacional?->numero_historia_clinica:' ',

        ];
    }
}
