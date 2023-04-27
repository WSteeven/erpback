<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteFinalRequest extends FormRequest
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
            'cliente' => 'required|numeric|integer',
            "id_cliente_final" => 'required|string',
            "nombres" => 'required|string',
            "apellidos" => 'nullable|string',
            "nombres" => 'nullable|string',
            "apellidos" => 'nullable|string',
            "celular" => 'nullable|string',
            "parroquia" => 'nullable|string',
            "direccion" => 'nullable|string',
            "referencia" => 'nullable|string',
            "coordenadas" => 'nullable|string',
            'correo' => 'nullable|string',
            'cedula' => 'nullable|string',
            'activo' => 'nullable|boolean',
            "provincia" => 'nullable|numeric|integer',
            "canton" => 'nullable|numeric|integer',
        ];
    }
}
