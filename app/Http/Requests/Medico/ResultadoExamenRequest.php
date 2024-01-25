<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class ResultadoExamenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'resultado' => 'required|string',
            'fecha_examen' => 'required|string',
            'configuracion_examen_id' => 'required|exists:med_configuraciones_examenes,id',
            'empleado_id' => 'required|exists:empleados,id',
        ];
    }
    protected function prepareForValidation()
    {
            $this->merge([
                'configuracion_examen_id' => $this->configuracion_examen,
                'empleado_id' => $this->empleado
            ]);
    }
}
