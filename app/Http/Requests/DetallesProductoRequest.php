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
            'codigo_barras' => 'nullable|string',
            'nombre_id' => 'required|integer|exists:nombres_de_productos,id',
            'descripcion' => 'required|string',
            'modelo_id' => 'required|integer|exists:modelos,id',
            'precio' => 'nullable|integer',
            'serial' => 'nullable|string',
            'categoria_id' => 'required|integer|exists:categorias,id',
            'tipo_fibra_id'=>'nullable|integer|exists:tipo_fibras,id',
            'hilo_id'=>'nullable|integer|exists:hilos,id',
            'punta_a' => 'nullable|integer',
            'punta_b' => 'nullable|integer',
            'punta_corte' => 'nullable|integer',
            "condicion_id"=>'required|exists:condiciones_de_productos,id'
            //'propietario_id'=>'required|integer|exists:propietarios,id'
        ];
        //Log::channel('testing')->info('LOG', ['entro en las reglas ']);
    }
}
