<?php

namespace App\Http\Requests\Medico;

// use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
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
            'ciu' => 'required|string',
            'establecimiento_salud' => 'nullable|string',
            'numero_historia_clinica' => 'required|string',
            'numero_archivo' => 'required|string',
            'puesto_trabajo' => 'required|string',
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
            'antecedentes_gineco_obstetricos.menarquia' => 'nullable|string',
            'antecedentes_gineco_obstetricos.ciclos' => 'nullable|integer',
            'antecedentes_gineco_obstetricos.fecha_ultima_menstruacion' => 'nullable|date_format:Y-m-d',
            'antecedentes_gineco_obstetricos.gestas' => 'nullable|integer',
            'antecedentes_gineco_obstetricos.partos' => 'nullable|integer',
            'antecedentes_gineco_obstetricos.cesareas' => 'nullable|integer',
            'antecedentes_gineco_obstetricos.abortos' => 'nullable|integer',
            'accidentesTrabajo.*.calificado_iss' => 'required|boolean',
            'fecha' => 'required|date_format:Y-m-d',
            'observacion' => 'required|string',
            'tipo_descripcion_antecedente_trabajo' => 'required|string',
            'constanteVital.presion_arterial' => 'required|string',
            'constanteVital.temperatura' => 'nullable|numeric',
            'constanteVital.frecuencia_cardiaca' => 'nullable|numeric',
            'constanteVital.saturacion_oxigeno' => 'nullable|numeric',
            'constanteVital.frecuencia_respiratoria' => 'nullable|numeric',
            'constanteVital.peso' => 'nullable|numeric',
            'constanteVital.talla' => 'nullable|numeric',
            'constanteVital.indice_masa_corporal' => 'nullable|numeric',
            'constanteVital.perimetro_abdominal' => 'nullable|numeric',
            // fr_puestos_trabajos_actuales
            'factoresRiesgoPuestoActual.*.puesto_trabajo' => 'required|string',
            'factoresRiesgoPuestoActual.*.actividad' => 'required|string',
            'factoresRiesgoPuestoActual.*.medidas_preventivas' => 'required|string',
            'factoresRiesgoPuestoActual.*.categorias' => 'required|array',
            // habitos_toxicos
            'habitosToxicos.*.tiempo_consumo_meses' => 'required|numeric',
            'habitosToxicos.*.cantidad' => 'required|numeric|integer',
            'habitosToxicos.*.ex_consumidor' => 'required|boolean',
            'habitosToxicos.*.tipo_habito_toxico_id' => 'required|exists:med_tipos_habitos_toxicos,id',
            'habitosToxicos.*.tiempo_abstinencia_meses' => 'required|numeric|integer',
            // actividades_fisicas
            'actividades_fisicas.*.nombre_actividad' => 'nullable|string',
            'actividades_fisicas.*.tiempo' => 'nullable|numeric',
            // medicaciones
            'medicaciones.*.nombre' => 'required|string',
            'medicaciones.*.cantidad' => 'required|string',
            // actividades_puestos_trabajos
            'actividades_puestos_trabajos.*.actividad' => 'required|string',
            // antecedentes_familiares
            'antecedentesFamiliares.*.descripcion' => 'sometimes|nullable|string',
            'antecedentesFamiliares.*.parentesco' => 'required|string',
            'antecedentesFamiliares.*.tipo_antecedente_familiar_id' => 'required|exists:med_tipos_antecedentes_familiares,id',
            // antecedentesEmpleosAnteriores
            'antecedentesEmpleosAnteriores.*.empresa' => 'required|string',
            'antecedentesEmpleosAnteriores.*.puesto_trabajo' => 'required|string',
            'antecedentesEmpleosAnteriores.*.actividades' => 'required|string',
            'antecedentesEmpleosAnteriores.*.tiempo_trabajo' => 'required|numeric',
            'antecedentesEmpleosAnteriores.*.observacion' => 'required|string',
            'antecedentesEmpleosAnteriores.*.riesgos' => 'required|array',
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
        ]);
    }
}
