<?php

namespace App\Http\Requests\ComprasProveedores;

use Illuminate\Foundation\Http\FormRequest;

class CategoriaOfertaProveedorRequest extends FormRequest
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
            'nombre' => 'required|string',
            'tipo_oferta' => 'required|exists:ofertas_proveedores,id',
            'estado' => 'boolean'
        ];
    }
}
