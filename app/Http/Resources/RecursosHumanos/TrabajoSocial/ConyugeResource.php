<?php

namespace App\Http\Resources\RecursosHumanos\TrabajoSocial;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConyugeResource extends JsonResource
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
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'nivel_academico' => $this->nivel_academico,
            'edad' => $this->edad,
            'profesion' => $this->profesion,
            'telefono' => $this->telefono,
            'tiene_dependencia_laboral' => $this->tiene_dependencia_laboral,
            'promedio_ingreso_mensual' => $this->promedio_ingreso_mensual,
        ];
    }
}
