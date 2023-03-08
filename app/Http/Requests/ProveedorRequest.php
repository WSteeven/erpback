<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProveedorRequest extends FormRequest
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
            'empresa' => 'required|exists:empresas,id|unique:proveedores,empresa_id,NULL,id', 
            'estado'=>'boolean'
        ];
    }

    public function messages()
    {
        return [
            'empresa'=>'Ya existe un proveedor registrado con esta razón social'
        ];
    }
}
