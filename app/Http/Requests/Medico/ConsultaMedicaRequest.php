<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class ConsultaMedicaRequest extends FormRequest
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
        $reglas = [
            'observacion' => 'nullable|string',
            'receta.rp' => 'nullable|string',
            'receta.prescripcion' => 'nullable|string',
            'diagnosticos.*.cie' => 'required|exists:med_cies,id',
            'diagnosticos.*.recomendacion' => 'required|string',
            'registro_empleado_examen' => 'nullable|numeric|integer|exists:med_registros_empleados_examenes,id',
            // 'registro_empleado_examen' => 'nullable|numeric|integer|exists:med_citas_medicas,id',
            'cita_medica' => 'nullable|numeric|integer|exists:med_citas_medicas,id',
            'dias_descanso' => 'nullable|numeric|integer',
            'dado_alta' => 'boolean',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $reglas['receta.rp'] = 'nullable|string';
            $reglas['receta.prescripcion'] = 'nullable|string';
        }

        return $reglas;
        /*return [
            'empleado_id'=> 'required|exists:empleados,id',
            'cita_id'=> 'required|exists:med_citas_medicas,id',
            'rp'=> 'required|string',
            'prescripcion'=> 'required|string',
            'diagnosticos.*.id' => 'nullable|exists:med_cies,id',
            'diagnosticos.*.recomendacion' => 'nullable|string',
        ];*/
    }

    /*protected function prepareForValidation()
    {
        $this->merge([
            'registro_empleado_examen_id' => $this->registro_empleado_examen,
            'cita_medica_id' => $this->cita_medica,
        ]);
    }*/
}
