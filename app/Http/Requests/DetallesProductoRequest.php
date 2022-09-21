<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class DetallesProductoRequest extends FormRequest
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
            'producto' => 'required|integer|exists:productos,id',
            'descripcion' => 'required|string',
            'modelo' => 'required|integer|exists:modelos,id',
            'precio_compra' => 'sometimes|numeric',
            'serial' => 'nullable|string|sometimes|unique:detalles_productos,serial',
            'tipo_fibra'=>'nullable|integer|exists:tipo_fibras,id',
            'hilos'=>'nullable|integer|exists:hilos,id',
            'punta_a' => 'nullable|integer',
            'punta_b' => 'nullable|integer',
            'punta_corte' => 'nullable|integer',
        ];
        //Log::channel('testing')->info('LOG', ['entro en las reglas ']);
    }

    public function messages()
    {
        return [
            'serial.unique'=>'Ya existe un detalle registrado con el mismo número de serie. Asegurate que el :attribute ingresado sea correcto'
        ];
    }
}
