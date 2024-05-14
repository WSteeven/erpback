<?php

namespace App\Http\Requests\Medico;

// use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class FichaPreocupacionalRequest extends FormRequest
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
            'establecimiento_salud' => 'nullable|string',
            // 'numero_historia_clinica' => 'required|string',
            'numero_archivo' => 'required|string',
            // 'puesto_trabajo' => 'required|string',
            'cargo_id' => 'required|numeric|integer|exists:cargos,id', // me quedé aqui <---
            'lateralidad' => 'required|string',
            'religion_id' => 'required|exists:med_religiones,id',
            'orientacion_sexual_id' => 'required|exists:med_orientaciones_sexuales,id',
            'identidad_genero_id' => 'required|exists:med_identidades_generos,id',
            'actividades_extralaborales' => 'nullable|string',
            'actividades_relevantes_puesto_trabajo_ocupar' => 'nullable|string',
            'motivo_consulta' => 'required|string',
            'antecedente_clinico_quirurgico' => 'sometimes|nullable|string',
            'registro_empleado_examen_id' => 'required|exists:med_registros_empleados_examenes,id',
            'enfermedad_actual' => 'sometimes|nullable|string',
            'recomendaciones_tratamiento' => 'nullable|string',
            'antecedentes_quirurgicos' => 'nullable|string',
            // antecedentes_gineco_obstetricos
            'antecedentes_gineco_obstetricos.menarquia' => 'nullable|string',
            'antecedentes_gineco_obstetricos.ciclos' => 'nullable|integer',
            'antecedentes_gineco_obstetricos.fecha_ultima_menstruacion' => 'nullable|date_format:Y-m-d',
            'antecedentes_gineco_obstetricos.gestas' => 'nullable|integer',
            'antecedentes_gineco_obstetricos.partos' => 'nullable|integer',
            'antecedentes_gineco_obstetricos.cesareas' => 'nullable|integer',
            'antecedentes_gineco_obstetricos.abortos' => 'nullable|integer',
            // Accidentes de trabajo
            'accidente_trabajo.calificado_iss' => 'required|boolean',
            'accidente_trabajo.observacion' => 'nullable|string',
            'accidente_trabajo.instituto_seguridad_social' => 'nullable|string',
            'accidente_trabajo.fecha' => 'nullable|string',
            // Enfermedad profesional
            'enfermedad_profesional.calificado_iss' => 'required|boolean',
            'enfermedad_profesional.observacion' => 'nullable|string',
            'enfermedad_profesional.instituto_seguridad_social' => 'nullable|string',
            'enfermedad_profesional.fecha' => 'nullable|string',
            // --
            'fecha' => 'required|date_format:Y-m-d',
            'observacion_examen_fisico_regional' => 'nullable|string',
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
            // fr_puestos_trabajos_actuales
            'factoresRiesgoPuestoActual.*.puesto_trabajo' => 'required|string',
            'factoresRiesgoPuestoActual.*.actividad' => 'required|string',
            'factoresRiesgoPuestoActual.*.medidas_preventivas' => 'required|string',
            'factoresRiesgoPuestoActual.*.categorias' => 'required|array',
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
            // actividades_puestos_trabajos
            'actividades_puestos_trabajos.*.actividad' => 'required|string',
            // antecedentes familiares
            'antecedentes_familiares.*.descripcion' => 'sometimes|nullable|string',
            'antecedentes_familiares.*.parentesco' => 'required|string',
            'antecedentes_familiares.*.tipo_antecedente_familiar_id' => 'required|exists:med_tipos_antecedentes_familiares,id',
            // antecedentesEmpleosAnteriores
            'antecedentes_empleos_anteriores.*.empresa' => 'required|string',
            'antecedentes_empleos_anteriores.*.puesto_trabajo' => 'required|string',
            'antecedentes_empleos_anteriores.*.actividades' => 'required|string',
            'antecedentes_empleos_anteriores.*.tiempo_trabajo' => 'required|numeric',
            'antecedentes_empleos_anteriores.*.observaciones' => 'required|string',
            'antecedentes_empleos_anteriores.*.tipos_riesgos_ids' => 'required|array',
            // antecedente_personal
            'antecedente_personal.vida_sexual_activa' => 'required|boolean',
            'antecedente_personal.hijos_vivos' => 'required|numeric|integer|min:0',
            'antecedente_personal.hijos_muertos' => 'required|numeric|integer|min:0',
            'antecedente_personal.tiene_metodo_planificacion_familiar' => 'required|boolean',
            'antecedente_personal.tipo_metodo_planificacion_familiar' => 'nullable|string',
            // revisiones organos sistemas
            'revisiones_actuales_organos_sistemas.*.organo_id' => 'required|numeric|integer',
            'revisiones_actuales_organos_sistemas.*.descripcion' => 'required|string',
            // aptitud_medica
            'aptitud_medica.tipo_aptitud_id' => 'required|numeric|integer',
            'aptitud_medica.observacion' => 'nullable|string',
            'aptitud_medica.limitacion' => 'nullable|string',
            // examenes fisicos regionales
            'examenes_fisicos_regionales.*.categoria_examen_fisico_id' => 'required|numeric|integer|exists:med_categorias_examenes_fisicos,id',
            'examenes_fisicos_regionales.*.observacion' => 'nullable|string',
            // examenes_realizados
            'examenes_realizados.*.examen_id' => 'required|numeric|integer|exists:med_examenes_organos_reproductivos,id',
            'examenes_realizados.*.tiempo' => 'required|numeric|integer',
            'examenes_realizados.*.resultado' => 'required|string',
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
