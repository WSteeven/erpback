<?php

namespace App\Http\Resources\Medico;

use App\Models\Empleado;
use App\Models\Medico\ProfesionalSalud;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class FichaAptitudResource extends JsonResource
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
            'observaciones_aptitud_medica' => $this->observaciones_aptitud_medica,
            'recomendaciones' => $this->recomendaciones,
            'tipo_evaluacion' => $this->tipo_evaluacion_id,
            'tipo_evaluacion_info' => $this->tipoEvaluacion !== null ? $this->tipoEvaluacion?->nombre : ' ',
            'tipo_aptitud_medica_laboral' => $this->tipo_aptitud_medica_laboral_id,
            'tipo_evaluacion_medica_retiro' => $this->tipo_evaluacion_medica_retiro_id,
            'codigo' => $this->profesionalSalud !== null ? $this->profesionalSalud->codigo : ' ',
            'opciones_respuestas_tipo_evaluacion_medica_retiro' => OpcionRespuestaTipoEvaluacionMedicaRetiroResource::collection($this->opcionesRespuestasTipoEvaluacionMedicaRetiro),
            'firmado_profesional_salud' => $this->firmado_profesional_salud,
            'firmado_paciente' => $this->firmado_paciente,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'fecha_emision' => $this->created_at,
            'paciente' => $this->registroEmpleadoExamen->empleado_id,
            'profesional_salud' => Empleado::extraerNombresApellidos(Empleado::find($this->profesional_salud_id)),
            'numero_historia' => null,
            'numero_archivo' => null,
        ];
    }
}
