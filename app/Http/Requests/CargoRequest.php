<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CargoRequest extends FormRequest
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
            'nombre' => 'required|unique:cargos'
        ];

        if(in_array($this->method(), ['PUT', 'PATCH'])){
            $cargo = $this->route()->parameter('cargo');

            $rules['nombre'] = ['required','string', Rule::unique('cargos')->ignore($cargo)];
        }

        return $rules;
    }
}
