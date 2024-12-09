<?php

namespace App\Http\Resources\RecursosHumanos\ControlPersonal;

use App\Models\Empleado;
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
            'empleado' =>Empleado::extraerNombresApellidos($this->empleado),
            'fecha' => $this->fecha ? $this->fecha->format('Y-m-d') : null,
            'hora_ingreso' => $this->hora_ingreso ? $this->hora_ingreso->format('H:i:s') : null, // Formatear como hora
            'hora_salida' => $this->hora_salida ? $this->hora_salida->format('H:i:s') : null,
            'hora_salida_almuerzo' => $this->hora_salida_almuerzo ? $this->hora_salida_almuerzo->format('H:i:s') : null,
            'hora_entrada_almuerzo' => $this->hora_entrada_almuerzo ? $this->hora_entrada_almuerzo->format('H:i:s') : null,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
