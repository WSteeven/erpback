<?php

namespace App\Http\Requests\SSO;

use Illuminate\Foundation\Http\FormRequest;

class CertificacionEmpleadoRequest extends FormRequest
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
        $rules = [
            'empleado_id' => 'required|numeric|integer|exists:empleados,id',
            'certificaciones_id' => 'required|array',
        ];

        // Para PATCH, solo validar los campos que se envían en la solicitud
        if ($this->isMethod('patch')) {
            $rules = collect($rules)->only(array_keys($this->all()))->toArray(); // Esta regla está bien para pach, verificado el 14/8/2024
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'empleado_id' => $this['empleado'],
            'certificaciones_id' => $this['certificaciones'],
        ]);
    }
}
