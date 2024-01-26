<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class EstadoSolicitudExamenRequest extends FormRequest
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
            'registro_id' =>  'required|exists:med_registros_empleados_examenes,id',
            'tipo_examen_id' => 'required|exists:med_tipos_examenes,id',
            'estado_examen_id' => 'required|exists:med_estados_examenes,id',
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'registro_id' => $this->registro_empleado_examen,
            'tipo_examen_id' => $this->tipo_examen,
            'estado_examen_id' => $this->estado_examen
        ]);
    }
}
