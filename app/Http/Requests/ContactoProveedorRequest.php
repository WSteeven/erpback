<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            "tipo_contacto" => 'required|string',
            "proveedor" => 'required|exists:proveedores,id',
        ];
    }
}
