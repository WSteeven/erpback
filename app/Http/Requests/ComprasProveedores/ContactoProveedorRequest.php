<?php

namespace App\Http\Requests\ComprasProveedores;

use App\Models\ComprasProveedores\ContactoProveedor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactoProveedorRequest extends FormRequest
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
            "nombres" => 'required|string',
            "apellidos" => 'required|string',
            "celular" => 'nullable|sometimes|string',
            "ext" => 'nullable|sometimes|string',
            "correo" => 'required|string',
            "tipo_contacto" => ['required', 'string', Rule::in([ContactoProveedor::COMERCIAL, ContactoProveedor::FINANCIERO, ContactoProveedor::TECNICO])],
            "empresa" => 'required|exists:empresas,id',
            "proveedor" => 'nullable|sometimes|exists:proveedores,id',
        ];
    }

    public function attributes()
    {
        return [
            'tipo_contacto' => 'tipo de contacto',
        ];
    }
    public function messages()
    {
        return [
            'tipo_contacto.in' => 'El campo :attribute solo acepta uno de los siguientes valores: COMERCIAL, TECNICO, FINANCIERO'
        ];
    }
}
