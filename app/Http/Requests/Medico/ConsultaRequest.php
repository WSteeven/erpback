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
            'empleado_id'=> 'required|exists:empleados,id',
            'diagnostico_cita_id'=> 'required|exists:med_diagnosticos_citas,id',
            'cita_id'=> 'required|exists:med_cies,id|unique:med_citas_medicas,id',
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'empleado_id' => $this->empleado,
            'diagnostico_cita_id' => $this->diagnostico_cita,
            'cita_id' => $this->cita,
        ]);
    }
}
