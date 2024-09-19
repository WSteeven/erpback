<?php

namespace App\Http\Resources\RecursosHumanos\SeleccionContratacion;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamenResource extends JsonResource
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
            'canton' => $this->canton_id,
            'direccion' => $this->direccion,
            'laboratorio' => $this->laboratorio,
            'indicaciones' => $this->indicaciones,
            'se_realizo_examen' => $this->se_realizo_examen,
            'es_apto' => $this->es_apto,
            'observacion' => $this->observacion,
        ];
    }
}
