<?php

namespace App\Http\Requests\Vehiculos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GarajeRequest extends FormRequest
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
        $rules =  [
            'nombre' => 'required|unique:veh_garajes',
            'activo' => 'boolean',
        ];
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $garaje = $this->route()->parameter('garaje');

            $rules['nombre'] = ['required', 'string', Rule::unique('veh_garajes')->ignore($garaje)];
        }

        return $rules;
    }
}
