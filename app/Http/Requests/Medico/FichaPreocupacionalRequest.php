<?php

namespace App\Http\Requests\Medico;

// use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

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
            'religion_id' => 'required|exists:med_religiones,id',
            'orientacion_sexual_id' => 'required|exists:med_orientaciones_sexuales,id',
            'identidad_genero_id' => 'required|exists:med_identidades_generos,id',
            'actividades_relevantes_puesto_trabajo_ocupar' => 'nullable|string',
            'motivo_consulta' => 'required|string',
            'registro_empleado_examen_id' => 'required|exists:med_registros_empleados_examenes,id',
            // 'actividad_fisica' => 'required|string',
            'enfermedad_actual' => 'nullable|string',
            'recomendaciones_tratamiento' => 'nullable|string',
            // 'descripcion_examen_fisico_regional' => 'required|string',
            // 'descripcion_revision_organos_sistemas' => 'required|string',
            'antecedentes_quirurgicos' => 'nullable|string',
            'antecedente_gineco_obstetrico.vida_sexual_activa' => 'nullable|boolean',
            'antecedente_gineco_obstetrico.tiene_metodo_planificacion_familiar' => 'nullable|boolean',
            'antecedente_gineco_obstetrico.tipo_metodo_planificacion_familiar' => 'nullable|string',
            'antecedente_gineco_obstetrico.menarquia' => 'nullable|date_format:Y-m-d',
            'antecedente_gineco_obstetrico.ciclos' => 'nullable|integer',
            'antecedente_gineco_obstetrico.fecha_ultima_menstruacion' => 'nullable|date_format:Y-m-d',
            'antecedente_gineco_obstetrico.gestas' => 'nullable|integer',
            'antecedente_gineco_obstetrico.partos' => 'nullable|integer',
            'antecedente_gineco_obstetrico.cesareas' => 'nullable|integer',
            'antecedente_gineco_obstetrico.abortos' => 'nullable|integer',
            'antecedente_gineco_obstetrico.hijos_vivos' => 'nullable|integer',
            'antecedente_gineco_obstetrico.hijos_muertos' => 'nullable|integer',
            // accidente_trabajo
            'accidente_trabajo.calificado_iess' => 'required|boolean',
            'accidente_trabajo.fecha' => 'nullable|date_format:Y-m-d',
            'accidente_trabajo.observacion' => 'nullable|string',
            'accidente_trabajo.tipo_descripcion_antecedente_trabajo' => 'nullable|string',
            'accidente_trabajo.descripcion' => 'nullable|string',
            // enfermedad_profesional
            'enfermedad_profesional.calificado_iess' => 'required|boolean',
            'enfermedad_profesional.fecha' => 'nullable|date_format:Y-m-d',
            'enfermedad_profesional.observacion' => 'nullable|string',
            'enfermedad_profesional.tipo_descripcion_antecedente_trabajo' => 'nullable|string',
            'enfermedad_profesional.descripcion' => 'nullable|string',
            // 'estatura' => 'required|numeric',
            // Constantes vitales y antropometria
            'presion_arterial' => 'nullable|string',
            'temperatura' => 'nullable|numeric',
            'frecuencia_cardiaca' => 'nullable|numeric',
            'saturacion_oxigeno' => 'nullable|numeric',
            'frecuencia_respiratoria' => 'nullable|numeric',
            'peso' => 'nullable|numeric',
            'talla' => 'nullable|numeric',
            'indice_masa_corporal' => 'nullable|numeric',
            'perimetro_abdominal' => 'nullable|numeric',
            // fr_puestos_trabajos_actuales
            'fr_puestos_trabajos_actuales.*.puesto_trabajo' => 'required|string',
            'fr_puestos_trabajos_actuales.*.actividad' => 'required|string',
            'fr_puestos_trabajos_actuales.*.medidas_preventivas' => 'required|string',
            'fr_puestos_trabajos_actuales.*.detalle_categ_factor_riesgo_fr_puesto_trab_act' => 'required',
            // habitos_toxicos
            'resultados_habitos_toxicos.*.tiempo_consumo_meses' => 'required|numeric',
            'resultados_habitos_toxicos.*.cantidad' => 'required|numeric|integer',
            'resultados_habitos_toxicos.*.ex_consumidor' => 'required|boolean',
            'resultados_habitos_toxicos.*.tipo_habito_toxico_id' => 'required|exists:med_tipos_habitos_toxicos,id',
            'resultados_habitos_toxicos.*.tiempo_abstinencia_meses' => 'required|numeric|integer',
            // actividades_fisicas
            'actividades_fisicas.*.nombre_actividad' => 'nullable|string',
            'actividades_fisicas.*.tiempo' => 'nullable|numeric',
            // medicaciones
            'medicaciones.*.nombre' => 'required|string',
            'medicaciones.*.cantidad' => 'required|numeric',
            // actividades_puestos_trabajos
            'actividades_puestos_trabajos.*.actividad' => 'required|string',
            // antecedentes_familiares
            'antecedentes_familiares.*.descripcion' => 'required|string',
            'antecedentes_familiares.*.tipo_antecedente_familiar_id' => 'required|exists:med_tipos_antecedentes_familiares,id',
            // antecedentes_empleos_anteriores
            'antecedentes_empleos_anteriores.*.empresa' => 'required|string',
            'antecedentes_empleos_anteriores.*.puesto_trabajo' => 'required|string',
            'antecedentes_empleos_anteriores.*.actividades_desempenaba' => 'required|string',
            'antecedentes_empleos_anteriores.*.tiempo_trabajo_meses' => 'required|numeric',
            'antecedentes_empleos_anteriores.*.r_fisico' => 'required|boolean',
            'antecedentes_empleos_anteriores.*.r_mecanico' => 'required|boolean',
            'antecedentes_empleos_anteriores.*.r_quimico' => 'required|boolean',
            'antecedentes_empleos_anteriores.*.r_biologico' => 'required|boolean',
            'antecedentes_empleos_anteriores.*.r_ergonomico' => 'required|boolean',
            'antecedentes_empleos_anteriores.*.r_phisosocial' => 'required|boolean',
            'antecedentes_empleos_anteriores.*.observacion' => 'required|string',
            // resultados_examenes_preocupacionales
            'resultados_examenes_preocupacionales.*.tiempo' => 'required|numeric',
            'resultados_examenes_preocupacionales.*.resultado' => 'required|string',
            'resultados_examenes_preocupacionales.*.tipo_antecedente_id' => 'required|exists:med_tipos_antecedentes,id',
            // 'examenes.*.genero' => 'required|string',
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
            'fecha' => Carbon::parse($this->fecha_ultima)->format('Y-m-d'),
            'menarquia' => Carbon::parse($this->menarquia)->format('Y-m-d')
        ]);
    }
}
