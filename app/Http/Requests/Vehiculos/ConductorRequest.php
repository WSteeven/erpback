<?php

namespace App\Http\Requests\Vehiculos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConductorRequest extends FormRequest
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
            'empleado' => 'required|exists:empleados,id|unique:veh_conductores,empleado_id',
            'tipo_licencia' => 'required|array',
            'inicio_vigencia' => 'required',
            'fin_vigencia' => 'required',
            'puntos' => 'required',
        ];
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['empleado'] = ['required'];
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->merge(['inicio_vigencia' => date('Y-m-d', strtotime($this->inicio_vigencia))]);
        $this->merge(['fin_vigencia' => date('Y-m-d', strtotime($this->fin_vigencia))]);
    }
}
