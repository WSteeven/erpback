<?php

namespace App\Http\Resources\RecursosHumanos\ControlPersonal;

use App\Models\Empleado;
use Illuminate\Http\Resources\Json\JsonResource;

class AtrasosResource extends JsonResource
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
            'empleado' => Empleado::extraerNombresApellidos($this->empleado), // Obtener nombre y apellidos del empleado
            'asistencia_id' => $this->asistencia_id, // ID de la asistencia relacionada
            'fecha_atraso' => $this->fecha_atraso ? $this->fecha_atraso->format('Y-m-d') : null, // Fecha del atraso
            'minutos_atraso' => $this->minutos_atraso, // Minutos de atraso calculados
            'segundos_atraso' => $this->segundos_atraso, // Segundos de atraso calculados
            'requiere_justificacion' => $this->requiere_justificacion, // Indicador de si requiere justificación
            'justificacion_atraso' => $this->justificacion_atraso, // Texto de la justificación
            'created_at' => $this->created_at->toDateTimeString(), // Fecha y hora de creación
            'updated_at' => $this->updated_at->toDateTimeString(), // Fecha y hora de actualización
        ];
    }
}
