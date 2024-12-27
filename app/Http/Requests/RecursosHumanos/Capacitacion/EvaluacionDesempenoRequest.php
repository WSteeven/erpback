<?php

namespace App\Http\Requests\RecursosHumanos\Capacitacion;

use Illuminate\Foundation\Http\FormRequest;

class EvaluacionDesempenoRequest extends FormRequest
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
            'evaluado_id' => 'required|exists:empleados,id',
            'evaluador_id' => 'required|exists:empleados,id',
            'calificacion' => 'required|numeric',
            'formulario_id' => 'required|exists:rrhh_cap_formularios,id',
            'respuestas' => 'required|array',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'evaluado_id' => $this->evaluado,
            'evaluador_id' => $this->evaluador,
            'formulario_id' => $this->formulario,
        ]);
    }
}
