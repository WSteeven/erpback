<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConceptoIngresoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'calculable_iess' => $this->calculable_iess,
            'abreviatura' => $this->abreviatura,
        ];
    }
}
