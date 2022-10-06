<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class DetalleProductoRequest extends FormRequest
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
        $rules = [
            'producto' => 'required|exists:productos,id',
            'descripcion' => 'required|string',
            'modelo' => 'required|exists:modelos,id',
            'precio_compra' => 'sometimes|numeric',
            'serial' => 'nullable|string|sometimes|unique:detalles_productos',
            'span'=>'nullable|integer|exists:spans,id',
            'tipo_fibra'=>'nullable|integer|exists:tipo_fibras,id',
            'hilos'=>'nullable|integer|exists:hilos,id',
            'punta_inicial' => 'nullable|integer',
            'punta_final' => 'nullable|integer',
            'custodia' => 'nullable|integer',
        ];

        if(in_array($this->method(), ['PUT', 'PATCH'])){
            $detalle = $this->route()->parameter('detalle');
            // Log::channel('testing')->info('Log', ['serial recibido:', $this->route()->parameter('detalle')]);
            // Log::channel('testing')->info('Log', ['serial encontrado:', $detalle->serial]);
            $rules['serial']=['nullable', 'string', 'sometimes', Rule::unique('detalles_productos')->ignore($detalle)];
            //$rules['serial']=['nullable', 'string', 'sometimes', Rule::unique('detalles_productos')->ignore($detalle['serial'])];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'serial.unique'=>'Ya existe un detalle registrado con el mismo número de serie. Asegurate que el :attribute ingresado sea correcto'
        ];
    }

    protected function prepareForValidation()
    {
        if(is_null($this->precio_compra)){
            $this->merge([
                'precio_compra'=>0
            ]);
        }
        if(is_null($this->custodia)){
            $this->merge([
                'custodia'=> $this->punta_inicial-$this->punta_final,
            ]);
        }
    }
}
