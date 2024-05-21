<?php

namespace App\Http\Requests\RecursosHumanos\SeleccionContratacion;

use Illuminate\Foundation\Http\FormRequest;

class PostulanteRequest extends FormRequest
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
            'nombres' => 'required|string' ,
            'apellidos' => 'required|string',
            'tipo_documento_identificacion' => 'required|string',
            'numero_documento_identificacion'=>'string|required|unique:rrhh_postulantes,numero_documento_identificacion|min:10',
            'telefono' => 'required|string' ,
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ];
    }
}
