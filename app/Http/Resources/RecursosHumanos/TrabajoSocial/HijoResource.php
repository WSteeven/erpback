<?php

namespace App\Http\Resources\RecursosHumanos\TrabajoSocial;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HijoResource extends JsonResource
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
            'ficha_id' => $this->ficha_id,
            'empleado_id' => $this->empleado_id,
            'nombres_apellidos' => $this->nombres_apellidos,
            'ocupacion' => $this->ocupacion,
            'edad' => $this->edad,
        ];
    }
}
