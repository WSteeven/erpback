<?php

namespace App\Http\Requests\Medico;

use App\Models\Medico\AccidenteEnfermedadLaboral;
use App\Models\Medico\DiagnosticoFicha;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
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
            // 'ciu' => 'required|string',
            // 'establecimiento_salud' => 'required|string',
            // 'numero_historia_clinica' => 'required|string',
            'numero_archivo' => 'required|string',
            'incidentes' => 'nullable|string',
            'cargo_id' => 'required|exists:cargos,id',
            'motivo_consulta' => 'required|string',
            'incidentes' => 'nullable|sometimes|string',
            'registro_empleado_examen_id' => 'required|exists:med_registros_empleados_examenes,id',
            'enfermedad_actual' => 'sometimes|nullable|string',
            'antecedentes_clinicos_quirurgicos' => 'sometimes|nullable|string',
            // Factores riesgo puesto actual 
            'factores_riesgo_puesto_actual.*.puesto_trabajo' => 'required|string',
            'factores_riesgo_puesto_actual.*.actividad' => 'required|string',
            'factores_riesgo_puesto_actual.*.tiempo_trabajo' => 'required|string|numeric|integer',
            'factores_riesgo_puesto_actual.*.medidas_preventivas' => 'required|string',
            'factores_riesgo_puesto_actual.*.categorias' => 'required|array',
            // 
            'diagnosticos' => 'sometimes|nullable|array',
            'diagnosticos.*.diagnostico_id' => 'required|exists:med_diagnosticos_cita_medica,id',
            'diagnosticos.*.tipo' => ['required', Rule::in(DiagnosticoFicha::PRESUNTIVO, DiagnosticoFicha::DEFINITIVO)],
            // examenes_realizados
            'examenes_realizados.*.examen_id' => 'required|numeric|integer|exists:med_examenes_organos_reproductivos,id',
            'examenes_realizados.*.tiempo' => 'required|numeric|integer',
            'examenes_realizados.*.resultado' => 'required|string',
            // habitos_toxicos
            'habitos_toxicos.*.tiempo_consumo_meses' => 'required|numeric',
            'habitos_toxicos.*.cantidad' => 'required|numeric|integer',
            'habitos_toxicos.*.ex_consumidor' => 'required|boolean',
            'habitos_toxicos.*.tipo_habito_toxico_id' => 'required|exists:med_tipos_habitos_toxicos,id',
            'habitos_toxicos.*.tiempo_abstinencia_meses' => 'nullable|numeric|integer',
            // actividades_fisicas
            'actividades_fisicas.*.nombre_actividad' => 'required|string',
            'actividades_fisicas.*.tiempo' => 'required|numeric',
            // medicaciones
            'medicaciones.*.nombre' => 'required|string',
            'medicaciones.*.cantidad' => 'required|string',
            // Accidentes de trabajo
            'accidente_trabajo.calificado_iss' => 'required|boolean',
            'accidente_trabajo.observacion' => 'nullable|string',
            'accidente_trabajo.instituto_seguridad_social' => 'nullable|string',
            'accidente_trabajo.fecha' => 'nullable|string',
            // 'accidentesTrabajo.*.tipo' => ['required', Rule::in(AccidenteEnfermedadLaboral::ACCIDENTE_TRABAJO)],
            // Enfermedad profesional
            'enfermedad_profesional.calificado_iss' => 'required|boolean',
            'enfermedad_profesional.observacion' => 'nullable|string',
            'enfermedad_profesional.instituto_seguridad_social' => 'nullable|string', // 'enfermedadesProfesionales.*.instituto_seguridad_social' => 'required_if:accidentesTrabajo.*.calificado_iss,true',
            'enfermedad_profesional.fecha' => 'nullable|string',
            // antecedentes familiares
            'antecedentes_familiares.*.descripcion' => 'sometimes|nullable|string',
            'antecedentes_familiares.*.parentesco' => 'required|string',
            'antecedentes_familiares.*.tipo_antecedente_familiar_id' => 'required|exists:med_tipos_antecedentes_familiares,id',
            // revisiones organos sistemas
            'revisiones_actuales_organos_sistemas.*.organo_id' => 'required|numeric|integer',
            'revisiones_actuales_organos_sistemas.*.descripcion' => 'required|string',
            // 'tipo_descripcion_antecedente_trabajo' => 'required|string',
            'constante_vital.presion_arterial' => 'required|string',
            'constante_vital.temperatura' => 'required|numeric',
            'constante_vital.frecuencia_cardiaca' => 'required|numeric',
            'constante_vital.saturacion_oxigeno' => 'required|numeric',
            'constante_vital.frecuencia_respiratoria' => 'required|numeric',
            'constante_vital.peso' => 'required|numeric',
            'constante_vital.talla' => 'required|numeric',
            'constante_vital.indice_masa_corporal' => 'required|numeric',
            'constante_vital.perimetro_abdominal' => 'required|numeric',
            // examenes fisicos regionales
            'examenes_fisicos_regionales.*.categoria_examen_fisico_id' => 'required|numeric|integer|exists:med_categorias_examenes_fisicos,id',
            'examenes_fisicos_regionales.*.observacion' => 'nullable|string',
            // aptitud_medica
            'aptitud_medica.tipo_aptitud_id' => 'required|numeric|integer',
            'aptitud_medica.observacion' => 'nullable|string',
            'aptitud_medica.limitacion' => 'nullable|string',
            //  profesional_salud_id
            'profesional_salud_id' => 'nullable|numeric|integer',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'religion_id' => $this->religion,
            'orientacion_sexual_id' => $this->orientacion_sexual,
            'identidad_genero_id' => $this->identidad_genero,
            'registro_empleado_examen_id' => $this->registro_empleado_examen,
            'fecha_ultima_menstruacion' => Carbon::parse($this->fecha_ultima_menstruacion)->format('Y-m-d'),
            'fecha' => Carbon::parse(Carbon::now())->format('Y-m-d'),
            'cargo_id' => $this->cargo,
            'profesional_salud_id' => Auth::user()->empleado->id,
        ]);
    }

    public function messages()
    {
        return [
            'aptitud_medica.tipo_aptitud_id.required' => 'Seleccione la aptitud médica para el trabajo.'
        ];
    }
}
