<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class ExamenPreocupacionalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [            'id' => $this->id,
        'nombre'=> $this->nombre,
        'tiempo'=> $this->tiempo,
        'resultados'=> $this->resultados,
        'genero'=> $this->genero,
        'antecedente_personal'=> $this->antecedente_personal_id,

    ];
    }
}
