<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class ConstanteVitalResource extends JsonResource
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
            'presion_arterial'=>$this->presion_arterial,
            'temperatura'=>$this->temperatura,
            'frecuencia_cardiaca'=>$this->frecuencia_cardiaca,
            'saturacion_oxigeno'=>$this->saturacion_oxigeno,
            'frecuencia_respiratoria'=>$this->frecuencia_respiratoria,
            'peso'=>$this->peso,
            'estatura'=>$this->estatura,
            'talla'=>$this->talla,
            'indice_masa_corporal'=>$this->indice_masa_corporal,
            'perimetro_abdominal'=>$this->perimetro_abdominal,
            'preocupacional_id'=>$this->preocupacional_id,
        ];
    }
}
