<?php

namespace App\Http\Resources\Medico;

use App\Models\Medico\AccidenteEnfermedadLaboral;
use Illuminate\Http\Resources\Json\JsonResource;

class FichaPeriodicaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();

        $modelo = [
            'id' => $this->id,
            'ciu' => $this->ciu,
            'establecimiento_salud' => $this->establecimiento_salud,
            'numero_historia_clinica' => $this->numero_historia_clinica,
            'numero_archivo' => $this->numero_archivo,
            'puesto_trabajo' => $this->puesto_trabajo,
            'motivo_consulta' => $this->motivo_consulta,
            'incidentes' => $this->incidentes,
            'registro_empleado_examen_id' => $this->registro_empleado_examen_id,
            'enfermedad_actual' => $this->enfermedad_actual,
            'observacion_examen_fisico_regional' => $this->observacion_examen_fisico_regional,
        ];

        if ($controller_method == 'show') {
            $modelo['antecedente_clinico_quirurgico'] = $this->antecedentesClinicos()->orderBy('id', 'desc')->first()->descripcion;
            $modelo['habitosToxicos'] = $this->habitosToxicos;
            $modelo['habitosToxicos'] = $this->habitosToxicos;
            $modelo['accidentesTrabajo'] = $this->accidentesEnfermedades()->where('tipo', AccidenteEnfermedadLaboral::ACCIDENTE_TRABAJO)->orderBy('id', 'desc')->first();
            $modelo['enfermedadesProfesionales'] = $this->accidentesEnfermedades()->where('tipo', AccidenteEnfermedadLaboral::ENFERMEDAD_PROFESIONAL)->orderBy('id', 'desc')->first();
            $modelo['antecedentesFamiliares'] = $this->antecedentesFamiliares;
            $modelo['factoresRiesgoPuestoActual'] = $this->frPuestoTrabajoActual;
            $modelo['revisionesOrganosSistemas'] = $this->revisionesActualesOrganosSistemas;
            $modelo['constanteVital'] = $this->constanteVital()->first();
            $modelo['examenesFisicosRegionales'] = $this->examenesFisicosRegionales;
            $modelo['diagnosticos'] = $this->diagnosticos;
            $modelo['aptitudMedica'] = $this->aptitudesMedicas()->first();
        }

        return $modelo;
    }
}
