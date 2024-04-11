<?php

namespace App\Http\Requests\Medico;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

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
            'establecimiento_salud' => 'required|string',
            'numero_historia_clinica' => 'required|string',
            'numero_archivo' => 'required|string',
            'puesto_trabajo' => 'required|string',
            'religion_id' => 'required|exists:med_religiones,id',
            'orientacion_sexual_id' => 'required|exists:med_orientaciones_sexuales,id',
            'identidad_genero_id' => 'required|exists:med_identidades_generos,id',
            'actividades_relevantes_puesto_trabajo_ocupar' => 'required|string',
            'motivo_consulta' => 'required|string',
            'registro_empleado_examen_id' => 'required|exists:med_registros_empleados_examenes,id',
            'actividad_fisica' => 'required|string',
            'enfermedad_actual' => 'required|string',
            'recomendaciones_tratamiento' => 'required|string',
            'descripcion_examen_fisico_regional' => 'required|string',
            'descripcion_revision_organos_sistemas' => 'required|string',
            'antecedentes_quirurgicos' => 'required|string',
            'vida_sexual_activa' => 'required|boolean',
            'tiene_metodo_planificacion_familiar' => 'required|boolean',
            'tipo_metodo_planificacion_familiar' => 'required|string',
            'menarquia' => 'required|date_format:Y-m-d',
            'ciclos' => 'required|integer',
            'fecha_ultima_menstruacion' => 'required|date_format:Y-m-d',
            'gestas' => 'required|integer',
            'partos' => 'required|integer',
            'cesareas' => 'required|integer',
            'abortos' => 'required|integer',
            'hijos_vivos' => 'required|integer',
            'hijos_muertos' => 'required|integer',
            'calificado_iess' => 'required|boolean',
            'descripcion' => 'required|string',
            'fecha' => 'required|date_format:Y-m-d',
            'observacion' => 'required|string',
            'tipo_descripcion_antecedente_trabajo' => 'required|string',
            'presion_arterial' => 'required|string',
            'temperatura' => 'required|numeric',
            'frecuencia_cardiaca' => 'required|numeric',
            'saturacion_oxigeno' => 'required|numeric',
            'frecuencia_respiratoria' => 'required|numeric',
            'peso' => 'required|numeric',
            'estatura' => 'required|numeric',
            'talla' => 'required|numeric',
            'indice_masa_corporal' => 'required|numeric',
            'perimetro_abdominal' => 'required|numeric',
            'habitos_toxicos.*.tipo_habito_toxico' => 'required|exists:med_tipos_habitos_toxicos,id',
            'habitos_toxicos.*.tiempo_consumo_meses' => 'required|numeric',
            'habitos_toxicos.*.tiempo_abstinencia_meses' => 'required|numeric',
            'actividades_fisicas.*.nombre_actividad' => 'required|string',
            'actividades_fisicas.*.tiempo' => 'required|numeric',
            'medicaciones.*.nombre' => 'required|string',
            'medicaciones.*.cantidad' => 'required|numeric',
            'actividades_puestos_trabajos.*.actividad' => 'required|string',
            'antecedentes_empleos_anteriores.*.empresa' => 'required|string',
            'antecedentes_empleos_anteriores.*.puesto_trabajo' => 'required|string',
            'antecedentes_empleos_anteriores.*.actividades_desempenaba' => 'required|string',
            'antecedentes_empleos_anteriores.*.tiempo_trabajo_meses' => 'required|numeric',
            'antecedentes_empleos_anteriores.*.r_fisico' => 'required|string',
            'antecedentes_empleos_anteriores.*.r_mecanico' => 'required|string',
            'antecedentes_empleos_anteriores.*.r_quimico' => 'required|string',
            'antecedentes_empleos_anteriores.*.r_biologico' => 'required|string',
            'antecedentes_empleos_anteriores.*.r_ergonomico' => 'required|string',
            'antecedentes_empleos_anteriores.*.r_phisosocial' => 'required|string',
            'antecedentes_empleos_anteriores.*.observacion' => 'required|string',
            'examenes.*.tiempo'=>'required|numeric',
            'examenes.*.resultados'=>'required|string',
            'examenes.*.genero'=>'required|string',
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
