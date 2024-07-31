<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModeloRequest extends FormRequest
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
            /* Funciona bien para la api, controla clave unica para dos campos

            'nombre' => 'required|string|unique:modelos,nombre,NULL,id,marca_id,'.$this->marca_id,
            'marca_id' => 'required|exists:marcas,id|unique:modelos,nombre', */

            //Configuracion para el front
            'nombre' => 'required|string|unique:modelos,nombre,NULL,id,marca_id,'.$this->marca,
            'marca' => 'required|exists:marcas,id',
        ];
    }
    public function messages()
    {
        return [
            'nombre.unique'=>'El modelo ya existe para la marca seleccionada', //Por favor verifica los modelos en el listado o ingresa uno diferente',
        ];
    }
}
