<?php

namespace App\Http\Requests\Medico;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class FichaAptitudRequest extends FormRequest
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
            'observaciones_aptitud_medica' => 'required|string',
            'recomendaciones' => 'nullable|string',
            'firmado_profesional_salud' => 'nullable|boolean',
            'firmado_paciente' => 'nullable|boolean',
            'registro_empleado_examen_id' => 'required|exists:med_registros_empleados_examenes,id',
            'tipo_aptitud_medica_laboral_id' => 'required|exists:med_tipos_aptitudes_medica_laborales,id',
            'profesional_salud_id' => 'nullable|exists:med_profesionales_salud,empleado_id',
            'opciones_respuestas_tipo_evaluacion_medica_retiro.*.respuesta' => 'nullable|string',
            'opciones_respuestas_tipo_evaluacion_medica_retiro.*.tipo_evaluacion_medica_retiro' => 'required|exists:med_tipos_evaluaciones_medica_retiros,id',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'tipo_aptitud_medica_laboral_id' => $this->tipo_aptitud_medica_laboral,
            'registro_empleado_examen_id' => $this->registro_empleado_examen,
            'profesional_salud_id' => $this->profesional_salud,
        ]);
    }

    public function messages()
    {
        return [
            'profesional_salud_id.exists' => 'Usted necesita estar registrado en la tabla de profesionales de salud para realizar esta operación.',
        ];
    }
}
