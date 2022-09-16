<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PerchaRequest extends FormRequest
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
            // Configuracion para la api
            'nombre' => 'required|string|unique:perchas,nombre,NULL,id,sucursal_id,'.$this->sucursal,
            'sucursal' => 'required|exists:sucursales,id|unique:perchas,nombre',           
        ];
    }
    public function messages()
    {
        return [
            'nombre.unique'=>'La percha ya existe en esta sucursal'
        ];
    }
}
