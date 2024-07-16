<?php

namespace App\Http\Requests\Vehiculos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TipoVehiculoRequest extends FormRequest
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
            'nombre' => 'required|string|unique:veh_tipos_vehiculos,nombre',
            'activo' => 'boolean',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $tipo = $this->route()->parameter('tipo');

            $rules['nombre'] = ['required', 'string', Rule::unique('veh_tipos_vehiculos')->ignore($tipo)];
        }

        return $rules;
    }
}
