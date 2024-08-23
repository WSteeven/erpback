<?php

namespace App\Http\Resources\RecursosHumanos\SeleccionContratacion;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BancoPostulanteResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'user_id'=>$this->user_id,
            'user_type'=>$this->user_type,
            'cargo_id'=>$this->cargo_id,
            'postulacion_id'=>$this->postulacion_id,
            'puntuacion'=>$this->puntuacion,
            'observacion'=>$this->observacion,
            'descartado'=>$this->descartado,
            'fue_contactado'=>$this->fue_contactado,
        ];
    }
}
