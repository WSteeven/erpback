<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FichaRetiroRequest extends FormRequest
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
            'ciu' => 'nullable|string',
            'establecimiento_salud' => 'nullable|string',
            'numero_historia_clinica' => 'nullable|string',
            'numero_archivo' => 'nullable|string',
            'puesto_trabajo' => 'nullable|string',
            'fecha_inicio_labores' => 'nullable|string',
            'fecha_salida' => 'nullable|string',
            'evaluacion_retiro' => 'nullable|string',
            'observacion_retiro' => 'nullable|string',
            'recomendacion_tratamiento' => 'nullable|string',
            'se_realizo_evaluacion_medica_retiro' => 'boolean',
            'observacion_evaluacion_medica_retiro' => 'nullable|string',
            'antecedentes_clinicos_quirurgicos' => 'nullable|string',
            'cargo_id' => 'required|exists:cargos,id',
            'registro_empleado_examen_id' => 'required|exists:med_registros_empleados_examenes,id',
            'profesional_salud_id' => 'nullable|exists:med_profesionales_salud,empleado_id',
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
            // Constante vital
            'constante_vital.presion_arterial' => 'required|string',
            'constante_vital.temperatura' => 'required|numeric',
            'constante_vital.frecuencia_cardiaca' => 'required|numeric',
            'constante_vital.saturacion_oxigeno' => 'required|numeric',
            'constante_vital.frecuencia_respiratoria' => 'required|numeric',
            'constante_vital.peso' => 'required|numeric',
            'constante_vital.talla' => 'required|numeric',
            'constante_vital.indice_masa_corporal' => 'required|numeric',
            'constante_vital.perimetro_abdominal' => 'required|numeric',
            // Examenes fisicos regionales
            'examenes_fisicos_regionales.*.categoria_examen_fisico_id' => 'required|numeric|integer|exists:med_categorias_examenes_fisicos,id',
            'examenes_fisicos_regionales.*.observacion' => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'registro_empleado_examen_id' => $this->registro_empleado_examen,
            'profesional_salud_id' => Auth::user()->empleado->id,
            'cargo_id' => $this->cargo,
        ]);
    }

    public function messages()
    {
        return [
            'profesional_salud_id.exists' => 'Usted necesita estar registrado en la tabla de profesionales de salud para realizar esta operación.',
        ];
    }
}
