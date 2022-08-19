<?php

namespace App\Http\Requests;

use App\Models\Empresa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmpresaRequest extends FormRequest
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
            'identificacion'=>'required|unique:empresas,identificacion',
            'tipo_contribuyente' => ['required', Rule::in([Empresa::NATURAL, Empresa::JURIDICA])],
            'razon_social' => 'string|required',
            'nombre_comercial' => 'string|nullable',
            'correo' => 'email|nullable',
            'direccion' => 'string|nullable',
        ];
        if(in_array($this->method(), ['PUT', 'PATCH'])){
            $empresa = $this->route()->parameter('empresa');

            $rules['identificacion']=['required','string',Rule::unique('empresas')->ignore($empresa)];
        }
        return $rules;
    }

    /**
     * Personalizacion de atributos y mensajes
     */
    public function attributes()
    {
        return [
            //'identificacion'=>'cedula/ruc',
            'tipo_contribuyente'=>'contribuyente'
        ];
    }
    public function messages()
    {
        return [
            'identificacion.required'=>'Debe ingresar una identificación de cedula o ruc',
            'tipo_contribuyente.in'=>'El campo :attribute solo acepta uno de los siguientes valores: NATURAL o JURIDICA'
        ];
    }
}
