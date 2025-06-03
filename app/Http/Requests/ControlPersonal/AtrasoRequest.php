<?php

namespace App\Http\Requests\ControlPersonal;

use Illuminate\Foundation\Http\FormRequest;

class AtrasoRequest extends FormRequest
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
            'justificador_id' => 'nullable|exists:empleados,id',
            'marcacion_id' => 'required|exists:rrhh_cp_marcaciones,id',
            'fecha_atraso' => 'required|string',
            'ocurrencia' => 'required|string',
            'segundos_atraso' => 'required|numeric',
            'justificado' => 'boolean',
            'justificacion' => 'nullable|string',
            'justificacion_atrasado' => 'nullable|string',
            'imagen_evidencia' => 'nullable|string',
            'revisado' => 'boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'empleado_id' => $this->empleado,
            'marcacion_id' => $this->marcacion,
        ]);
        if ($this->empleado === auth()->user()->empleado->id && is_null($this->justificador)) {
            $this->merge(['justificador_id' => null]);
        } else {
            $this->merge([
                'justificador_id' => $this->justificador ?: auth()->user()->empleado->id,
            ]);

        }
    }
}
