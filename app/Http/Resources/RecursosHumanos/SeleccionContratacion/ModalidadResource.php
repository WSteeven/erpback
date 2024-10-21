<?php

namespace App\Http\Resources\RecursosHumanos\SeleccionContratacion;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModalidadResource extends JsonResource
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
            'id' => $this->id,
            'nombre'=>$this->nombre,
            'activo'=>$this->activo,
        ];
    }
}
