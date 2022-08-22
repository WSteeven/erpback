<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SucursalRequest extends FormRequest
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
            'lugar'=>'required|unique:sucursales|string',
            'telefono'=>'required|string|min:7|max:10',
            'correo'=>'required|email',
        ];

        if(in_array($this->method(), ['PUT', 'PATCH'])){
            $sucursal = $this->route()->parameter('sucursal');

            $rules['lugar']=['required','string',Rule::unique('sucursales')->ignore($sucursal)];
        }
        return $rules;
    }
}
