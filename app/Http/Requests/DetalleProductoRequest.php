<?php

namespace App\Http\Requests;

use App\Models\Categoria;
use App\Models\DetalleProducto;
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
            'marca' => 'required|exists:marcas,id',
            'modelo' => 'required|exists:modelos,id',
            'precio_compra' => 'sometimes|numeric',
            'serial' => 'nullable|string|sometimes|unique:detalles_productos',
            'lote' => 'nullable|string|sometimes|unique:detalles_productos',
            'span' => 'nullable|integer|exists:spans,id',
            'tipo_fibra' => 'nullable|integer|exists:tipo_fibras,id',
            'hilos' => 'nullable|integer|exists:hilos,id',
            'punta_inicial' => 'nullable|integer',
            'punta_final' => 'nullable|integer',
            'custodia' => 'nullable|integer',

            'procesador' => 'nullable|sometimes|exists:procesadores,id|required_with_all:ram,disco',
            'ram' => 'nullable|sometimes|exists:rams,id|required_with_all:procesador,disco',
            'disco' => 'nullable|sometimes|exists:discos,id|required_with_all:ram,procesador',
            'imei' => 'nullable|sometimes|numeric',

            'color' => 'sometimes|nullable|string',
            'talla' => 'sometimes|nullable|string',
            'calibre' => 'sometimes|nullable|string',
            'peso' => 'sometimes|nullable|string',
            'dimensiones' => 'sometimes|nullable|string',
            'permiso' => 'sometimes|nullable|string',
            'permiso_id' => 'sometimes|nullable|exists:bod_permisos_armas,id',
            'caducidad' => 'sometimes|nullable|string',

            'es_fibra' => 'boolean',
            'esActivo' => 'boolean',
            'tipo' => ['sometimes', 'nullable', Rule::in([DetalleProducto::HOMBRE, DetalleProducto::MUJER])],

            'fecha_caducidad' => 'nullable|date_format:Y-m-d',
            'fotografia' => 'nullable|string',
            'fotografia_detallada' => 'nullable|string',
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
    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $detalle = DetalleProducto::where('descripcion', $this->descripcion)->where('serial', $this->serial)->first();
            Log::channel('testing')->info('Log', ['El detalle encontrado es: ', $detalle]);
            if (!is_null($detalle)) {
                Log::channel('testing')->info('Log', ['Hay un detalle: ', $detalle]);
                if ($detalle->descripcion === strtoupper($this->descripcion) && strtoupper($this->serial) === $detalle->serial && count($this->seriales) < 1) $validator->errors()->add('descripcion', 'Ya hay un detalle registrado con la misma descripción');
            }
        });
    }
    protected function prepareForValidation()
    {
        if (is_null($this->precio_compra)) {
            $this->merge([
                'precio_compra' => 0
            ]);
        }
        if (is_null($this->custodia)) {
            $this->merge([
                'custodia' => abs($this->punta_inicial - $this->punta_final),
            ]);
        }
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $this->merge([
                'custodia' => abs($this->punta_inicial - $this->punta_final),
            ]);
        }
    }
}
