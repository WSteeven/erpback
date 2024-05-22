<?php

namespace App\Http\Requests\Medico;

use App\Models\Medico\AccidenteEnfermedadLaboral;
use App\Models\Medico\DiagnosticoFicha;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FichaPeriodicaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'ciu' => 'required|string',
            'establecimiento_salud' => 'required|string',
            'numero_historia_clinica' => 'required|string',
            'numero_archivo' => 'required|string',
            'puesto_trabajo' => 'required|string',
            'motivo_consulta' => 'required|string',
            'incidentes' => 'nullable|sometimes|string',
            'registro_empleado_examen_id' => 'required|exists:med_registros_empleados_examenes,id',
            'enfermedad_actual' => 'sometimes|nullable|string',
            'antecedente_clinico_quirurgico' => 'sometimes|nullable|string',
            'habitosToxicos' => 'nullable|string',
            'habitosToxicos.*.tiempo_consumo_meses' => 'required|numeric',
            'habitosToxicos.*.cantidad' => 'required|numeric|integer',
            'habitosToxicos.*.ex_consumidor' => 'required|boolean',
            'habitosToxicos.*.tipo_habito_toxico_id' => 'required|exists:med_tipos_habitos_toxicos,id',
            'habitosToxicos.*.tiempo_abstinencia_meses' => 'required|numeric|integer',
            'actividadesFisicas' => 'sometimes|nullable|array',
            'actividadesFisicas.*.nombre_actividad' => 'required|string',
            'actividadesFisicas.*.tiempo' => 'required|string',
            'medicaciones' => 'sometimes|nullable|array',
            'medicaciones.*.nombre' => 'required|string',
            'medicaciones.*.cantidad' => 'required|string',
            'accidentesTrabajo' => 'sometimes|nullable|string',
            'accidentesTrabajo.*.tipo' => ['required', Rule::in(AccidenteEnfermedadLaboral::ACCIDENTE_TRABAJO)],
            'accidentesTrabajo.*.observacion' => 'nullable|string',
            'accidentesTrabajo.*.calificado_iss' => 'boolean',
            'accidentesTrabajo.*.instituto_seguridad_social' => 'required_if:accidentesTrabajo.*.calificado_iss,true',
            'accidentesTrabajo.*.fecha' => 'required|string',
            'enfermedadesProfesionales' => 'sometimes|nullable|string',
            'enfermedadesProfesionales.*.tipo' => ['required', Rule::in(AccidenteEnfermedadLaboral::ENFERMEDAD_PROFESIONAL)],
            'enfermedadesProfesionales.*.observacion' => 'nullable|string',
            'enfermedadesProfesionales.*.calificado_iss' => 'boolean',
            'enfermedadesProfesionales.*.instituto_seguridad_social' => 'required_if:accidentesTrabajo.*.calificado_iss,true',
            'enfermedadesProfesionales.*.fecha' => 'required|string',
            'antecedentesFamiliares.*.descripcion' => 'sometimes|nullable|string',
            'antecedentesFamiliares.*.parentesco' => 'required|string',
            'antecedentesFamiliares.*.tipo_antecedente_familiar_id' => 'required|exists:med_tipos_antecedentes_familiares,id',
            'factoresRiesgoPuestoActual.*.puesto_trabajo' => 'required|string',
            'factoresRiesgoPuestoActual.*.actividad' => 'required|string',
            'factoresRiesgoPuestoActual.*.tiempo_trabajo' => 'required|string|numeric|integer',
            'factoresRiesgoPuestoActual.*.medidas_preventivas' => 'required|string',
            'factoresRiesgoPuestoActual.*.categorias' => 'required|array',
            'revisionesOrganosSistemas' => 'required|array',
            'revisionesOrganosSistemas.*.organo_id' => 'required|exists:med_sistemas_organicos,id',
            'revisionesOrganosSistemas.*.descripcion' => 'required|string',

            'constanteVital.presion_arterial' => 'required|string',
            'constanteVital.temperatura' => 'nullable|numeric',
            'constanteVital.frecuencia_cardiaca' => 'nullable|numeric',
            'constanteVital.saturacion_oxigeno' => 'nullable|numeric',
            'constanteVital.frecuencia_respiratoria' => 'nullable|numeric',
            'constanteVital.peso' => 'nullable|numeric',
            'constanteVital.talla' => 'nullable|numeric',
            'constanteVital.indice_masa_corporal' => 'nullable|numeric',
            'constanteVital.perimetro_abdominal' => 'nullable|numeric',
            'examenesFisicosRegionales' => 'sometimes|nullable|array',
            'examenesFisicosRegionales.*.categoria_examen_fisico_id' => 'required|exists:med_categorias_examenes_fisicos,id',
            'examenesFisicosRegionales.*.observacion' => 'required|string',
            'diagnosticos' => 'sometimes|nullable|array',
            'diagnosticos.*.diagnostico_id' => 'required|exists:med_diagnosticos_cita_medica,id',
            'diagnosticos.*.tipo' => ['required', Rule::in(DiagnosticoFicha::PRESUNTIVO, DiagnosticoFicha::DEFINITIVO)],
            'aptitudMedica.tipo_aptitud_id' => 'required|exists:med_tipos_aptitudes,id',
            'aptitudMedica.observacion' => 'sometimes|nullable|string',
            'aptitudMedica.limitacion' => 'sometimes|nullable|string',
        ];
    }
}
