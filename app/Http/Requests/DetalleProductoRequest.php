<?php

namespace App\Http\Requests;

use App\Models\Categoria;
use App\Models\Producto;
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
            'span' => 'nullable|integer|exists:spans,id',
            'tipo_fibra' => 'nullable|integer|exists:tipo_fibras,id',
            'hilos' => 'nullable|integer|exists:hilos,id',
            'punta_inicial' => 'nullable|integer',
            'punta_final' => 'nullable|integer',
            'custodia' => 'nullable|integer',

            'procesador' => 'nullable|sometimes|exists:procesadores,id|required_with_all:ram,disco',
            'ram' => 'nullable|sometimes|exists:rams,id|required_with_all:procesador,disco',
            'disco' => 'nullable|sometimes|exists:discos,id|required_with_all:ram,procesador',

            'color' => 'sometimes|nullable|string',
            'talla' => 'sometimes|nullable|string',
            'capacidad' => 'sometimes|nullable|string',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $detalle = $this->route()->parameter('detalle');
            // Log::channel('testing')->info('Log', ['serial recibido:', $this->route()->parameter('detalle')]);
            $rules['serial'] = ['nullable', 'string', 'sometimes', Rule::unique('detalles_productos')->ignore($detalle)];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'serial.unique' => 'Ya existe un detalle registrado con el mismo número de serie. Asegurate que el :attribute ingresado sea correcto'
        ];
    }

    /* public function withValidator($validator){
        $validator->after(function ($validator){
            $producto = Producto::where('id',$this->producto)->get();
            Log::channel('testing')->info('Log', ['producto', $producto->id]);
            $categoria = Categoria::where('id', $producto->categoria)->get();
            Log::channel('testing')->info('Log', ['categoria', $categoria]);
            // $cat = $producto->categoria;
            // Log::channel('testing')->info('Log', ['categoria', $cat]);
            if($producto->categoria()->nombre==='INFORMATICA' || $producto->categoria()->nombre==='EQUIPOS'){
                $validator->errors()->add('serial', 'Es necesario un numero de serie para categoría EQUIPOS e INFORMÁTICA');
            }
        });
    } */

    protected function prepareForValidation()
    {
        if (is_null($this->precio_compra)) {
            $this->merge([
                'precio_compra' => 0
            ]);
        }
        if (is_null($this->custodia)) {
            $this->merge([
                'custodia' => $this->punta_inicial - $this->punta_final,
            ]);
        }
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $this->merge([
                'custodia' => $this->punta_inicial - $this->punta_final,
            ]);
        }
    }
}
