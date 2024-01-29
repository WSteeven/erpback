<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class AntecedenteTrabajoAnteriorResource extends JsonResource
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
            'id'=>$this->id,
            'puesto_trabajo' => $this->puesto_trabajo,
            'actividades_desempenaba' => $this->actividades_desempenaba,
            'tiempo_tabajo'=>$this->tiempo_tabajo,
            'r_fisico'=>$this->r_fisico,
            'r_mecanico'=>$this->r_mecanico,
            'r_quimico'=>$this->r_quimico,
            'r_biologico'=>$this->r_biologico,
            'r_ergonomico'=>$this->r_ergonomico,
            'r_phisosocial'=>$this->r_phisosocial,
            'observacion'=>$this->observacion,
            'preocupacional_id'=>$this->preocupacional_id,
        ];
    }
}
