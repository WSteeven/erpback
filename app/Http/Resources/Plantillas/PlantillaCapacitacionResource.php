<?php

namespace App\Http\Resources\Plantillas;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\EmpleadoResource;

class PlantillaCapacitacionResource extends JsonResource
{
    /**
     * Transformar el recurso en un array para la respuesta JSON.
     */
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'tema'        => $this->tema,
            'fecha'       => $this->fecha,
            'hora_inicio' => $this->hora_inicio,
            'hora_fin'    => $this->hora_fin,
            'duracion'    => $this->duracion, // del accessor en el modelo
            'modalidad'   => $this->modalidad,

            // Capacitador (nombre)
            'capacitador_nombre' => $this->whenLoaded('capacitador', function () {
                return $this->capacitador->nombres . ' ' . $this->capacitador->apellidos;
            }),

            // Capacitador (empleado que dicta la capacitación)
            'capacitador' => $this->whenLoaded('capacitador', function () {
                return [
                    'id'        => $this->capacitador->id,
                    'nombres'   => $this->capacitador->nombres,
                    'apellidos' => $this->capacitador->apellidos,
                    'departamento' => optional($this->capacitador->departamento)->nombre,
                ];
            }),

            // Asistentes
            'asistentes'  => $this->whenLoaded('asistentes', function () {
                return $this->asistentes->map(function ($a) {
                    return [
                        'id'        => $a->id,
                        'nombres'   => $a->nombres,
                        'apellidos' => $a->apellidos,
                        'identificacion' => $a->identificacion,
                        'departamento'   => optional($a->departamento)->nombre,
                    ];
                });
            }),
        ];
    }
}
