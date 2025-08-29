<?php

namespace App\Http\Resources\RecursosHumanos\SeleccionContratacion;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntrevistaResource extends JsonResource
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
            'id' => $this->postulacion_id,
            'postulacion_id' => $this->postulacion_id,
            'fecha_hora' => $this->fecha_hora,
            'duracion' => $this->duracion,
            'reagendada' => $this->reagendada,
            'presencial' => $this->presencial,
            'link' => $this->link,
            'canton' => $this->canton_id,
            'direccion' => $this->direccion,
            'nueva_fecha_hora' => $this->nueva_fecha_hora,
            'observacion' => $this->observacion,
            'asistio' => $this->asistio
        ];
    }
}
