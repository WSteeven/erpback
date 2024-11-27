<?php

namespace App\Http\Resources\RecursosHumanos\ControlPersonal;

use Illuminate\Http\Resources\Json\JsonResource;

class AsistenciaResource extends JsonResource
{
    /**
     * Transformar el recurso en un array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'empleado' => [
                'id' => $this->empleado->id,
                'nombre_completo' => $this->empleado->nombres . ' ' . $this->empleado->apellidos,
            ],
            'hora_ingreso' => $this->hora_ingreso,
            'hora_salida' => $this->hora_salida,
            'hora_salida_almuerzo' => $this->hora_salida_almuerzo,
            'hora_entrada_almuerzo' => $this->hora_entrada_almuerzo,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
