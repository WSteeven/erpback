<?php

namespace App\Http\Requests\Ventas;

use Illuminate\Foundation\Http\FormRequest;
use Src\Shared\ValidarIdentificacion;

class ClienteClaroRequest extends FormRequest
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
            'identificacion'=> 'required',
            'nombres'=> 'required',
            'apellidos'=> 'required',
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $validador = new ValidarIdentificacion();
            if (!$validador->validarCedula($this->identificacion)) {
                $validator->errors()->add('identificacion', 'La identificación no pudo ser validada, verifica que sea una cédula válida');
            }
        });
    }
}
