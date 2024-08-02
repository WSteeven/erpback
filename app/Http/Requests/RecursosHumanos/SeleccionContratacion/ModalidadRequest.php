<?php

namespace App\Http\Requests\RecursosHumanos\SeleccionContratacion;

use Illuminate\Foundation\Http\FormRequest;

class ModalidadRequest extends FormRequest
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
            'nombre'=>'required|string|unique:rrhh_contratacion_modalidades,nombre',
            'activo'=>'boolean'
        ];
    }
}
