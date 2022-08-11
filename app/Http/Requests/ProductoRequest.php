<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class ProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'codigo_barras' => 'nullable|string',
            'nombre_id'=> 'required|integer',
            'descripcion'=> 'required|string',
            'modelo_id'=> 'required|integer',
            'precio'=> 'nullable|integer',
            'serial'=>'nullable|string',
            'categoria_id'=> 'required|integer',
            //'estado'=>['nullable', Rule::in(Producto::ACTIVO, Producto::INACTIVO)]
        ];
        Log::channel('testing')->info('LOG', ['entro en las reglas ']);
    }
}
