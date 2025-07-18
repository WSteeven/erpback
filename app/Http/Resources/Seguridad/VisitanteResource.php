<?php

namespace App\Http\Resources\Seguridad;

use App\Models\Empleado;
use Illuminate\Http\Resources\Json\JsonResource;

class VisitanteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this['id'],
            'nombre_completo' => $this['nombre_completo'],
            'identificacion' => $this['identificacion'],
            'celular' => $this['celular'],
            'motivo_visita' => $this['motivo_visita'],
            'persona_visitada' => Empleado::extraerApellidosNombres($this->personaVisitada),
            'placa_vehiculo' => $this['placa_vehiculo'],
            'fecha_hora_ingreso' => $this['fecha_hora_ingreso'],
            'fecha_hora_salida' => $this['fecha_hora_salida'],
            'observaciones' => $this['observaciones'],
            'actividad_bitacora_id' => $this['actividad_bitacora_id'],
        ];
    }
}
