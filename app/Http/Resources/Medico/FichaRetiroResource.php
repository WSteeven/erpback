<?php

namespace App\Http\Resources\Medico;

use App\Models\Medico\AccidenteEnfermedadLaboral;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\App\Medico\FichasMedicasService;

class FichaRetiroResource extends JsonResource
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
            'establecimiento_salud' => $this->establecimiento_salud,
            'numero_historia_clinica' => $this->numero_historia_clinica,
            'numero_archivo' => $this->numero_archivo,
            'fecha_inicio_labores' => $this->fecha_inicio_labores,
            'fecha_salida' => $this->fecha_salida,
            'evaluacion_retiro' => $this->evaluacion_retiro,
            'observacion_retiro' => $this->observacion_retiro,
            'recomendacion_tratamiento' => $this->recomendacion_tratamiento,
            'se_realizo_evaluacion_medica_retiro' => $this->se_realizo_evaluacion_medica_retiro,
            'observacion_evaluacion_medica_retiro' => $this->observacion_evaluacion_medica_retiro,
            'antecedentes_clinicos_quirurgicos' => $this->antecedentes_clinicos_quirurgicos,
            'registro_empleado_examen_id' => $this->registro_empleado_examen_id,
            'profesional_salud_id' => $this->profesional_salud_id,
            'cargo' => $this->cargo_id,
            // Otros
            'accidente_trabajo' => FichasMedicasService::mapearAccidenteTrabajo($this, AccidenteEnfermedadLaboral::ACCIDENTE_TRABAJO), //->mapearAccidenteTrabajo($this->accidentesEnfermedades()->where('tipo', AccidenteEnfermedadLaboral::ACCIDENTE_TRABAJO)->orderBy('id', 'desc')->first()),
            'enfermedad_profesional' => FichasMedicasService::mapearAccidenteTrabajo($this, AccidenteEnfermedadLaboral::ENFERMEDAD_PROFESIONAL), //->mapearAccidenteTrabajo($this->accidentesEnfermedades()->where('tipo', AccidenteEnfermedadLaboral::ACCIDENTE_TRABAJO)->orderBy('id', 'desc')->first()),
            // 'enfermedad_profesional' => $this->mapearAccidenteTrabajo($this->accidentesEnfermedades()->where('tipo', AccidenteEnfermedadLaboral::ENFERMEDAD_PROFESIONAL)->orderBy('id', 'desc')->first()),
            // Constante vital
            'constante_vital' => [
                'presion_arterial' => $this->constanteVital()->first()?->presion_arterial,
                'temperatura' => $this->constanteVital()->first()?->temperatura,
                'frecuencia_cardiaca' => $this->constanteVital()->first()?->frecuencia_cardiaca,
                'saturacion_oxigeno' => $this->constanteVital()->first()?->saturacion_oxigeno,
                'frecuencia_respiratoria' => $this->constanteVital()->first()?->frecuencia_respiratoria,
                'peso' => $this->constanteVital()->first()?->peso,
                'estatura' => $this->constanteVital()->first()?->estatura,
                'talla' => $this->constanteVital()->first()?->talla,
                'indice_masa_corporal' => $this->constanteVital()->first()?->indice_masa_corporal,
                'perimetro_abdominal' => $this->constanteVital()->first()?->perimetro_abdominal,
            ],
            // Examenes fisicos regionales
            'examenes_fisicos_regionales' => FichasMedicasService::mapearExamenesFisicosRegionales($this),
        ];
    }
}
