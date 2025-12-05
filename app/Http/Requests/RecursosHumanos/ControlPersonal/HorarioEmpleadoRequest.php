<?php

namespace App\Http\Requests\RecursosHumanos\ControlPersonal;

use Illuminate\Foundation\Http\FormRequest;

class HorarioEmpleadoRequest extends FormRequest
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
            'empleado_id' => 'required|exists:empleados,id',
            'horario_id' => 'required|exists:rrhh_cp_horario_laboral,id',
            'fecha_inicio' => 'required|string',
            'fecha_fin' => 'sometimes|nullable|string',
            'activo' => 'boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'empleado_id' => $this->empleado,
            'horario_id' => $this->horario,
        ]);
    }
}
