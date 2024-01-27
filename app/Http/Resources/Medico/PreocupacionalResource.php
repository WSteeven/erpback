<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class PreocupacionalResource extends JsonResource
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
            'id' => $this->id,
            'ciu' => $this->ciu,
            'esatblecimiento_salud' => $this->esatblecimiento_salud,
            'numero_historia_clinica' => $this->numero_historia_clinica,
            'numero_archivo' => $this->numero_archivo,
            'puesto_trabajo' => $this->puesto_trabajo,
            'religion' => $this->religion_id,
            'religion_info' => $this->religion !== null ? $this->religion?->nombre:' ',
            'orientacion_sexual' => $this->orientacion_sexual_id,
            'orientacion_sexual_info' => $this->orientacionSexual !== null ? $this->orientacionSexual?->nombre:' ',
            'identidad_genero' => $this->identidad_genero_id,
            'identidad_genero_info' => $this->identidadGenero !== null ? $this->identidadGenero?->nombre:'',
            'actividades_relevantes_puesto_trabajo_ocupar' => $this->actividades_relevantes_puesto_trabajo_ocupar,
            'motivo_consulta' => $this->motivo_consulta,
            'empleado' => $this->empleado_id,
            'empleado_info' => $this->empleado !== null ? $this->empleado?->nombres.''.$this->empleado?->apellidos:' ',
            'actividad_fisica' => $this->actividad_fisica,
            'enfermedad_actual' => $this->enfermedad_actual,
            'recomendaciones_tratamiento' => $this->recomendaciones_tratamiento,
        ];
    }
}
