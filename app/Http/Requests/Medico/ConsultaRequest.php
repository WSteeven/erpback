<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class ConsultaRequest extends FormRequest
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
            'empleado_id'=> 'required|exists:empleados,id',
            'cita_id'=> 'required|exists:med_cies,id|unique:med_citas_medicas,id',
            'rp'=> 'required|string',
            'prescripcion'=> 'required|string',
            'diagnosticos.*.cie' => 'nullable|exists:med_cies,id',
            'diagnosticos.*.recomendacion' => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'empleado_id' => $this->empleado,
            'cita_id' => $this->cita,
        ]);
    }
}
