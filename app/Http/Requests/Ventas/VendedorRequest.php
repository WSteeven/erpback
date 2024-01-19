<?php

namespace App\Http\Requests\Ventas;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VendedorRequest extends FormRequest
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
            'empleado_id' => 'required|integer|unique:ventas_vendedores,empleado_id',
            'modalidad_id' => 'required|integer',
            'tipo_vendedor' => 'required',
            'jefe_inmediato_id' => 'required|integer',
            'activo' => 'boolean',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $vendedor = $this->route()->parameter('vendedor');

            $rules['empleado_id'] = ['required',  Rule::unique('ventas_vendedores')->ignore($vendedor)];
        }

        return $rules;
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'empleado_id' => $this->empleado,
            'modalidad_id' => $this->modalidad,
            'jefe_inmediato_id' => $this->jefe_inmediato
        ]);
    }
}
