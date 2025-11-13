<?php

namespace App\Http\Requests;

use App\Models\DetalleProducto;
use Illuminate\Foundation\Http\FormRequest;
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
            'vida_util' => 'sometimes|nullable|numeric',
            'serial' => 'nullable|string|sometimes|unique:detalles_productos',

            // Reglas para varios items
            'varios_items' => 'boolean',
            'seriales' => 'required_if:varios_items,true|array',
            'seriales.*.serial' => 'required|string',

            'lote' => 'nullable|string|sometimes|unique:detalles_productos',
            'span' => 'nullable|integer|exists:spans,id',
            'tipo_fibra' => 'nullable|integer|exists:tipo_fibras,id',
            'hilos' => 'nullable|integer|exists:hilos,id',
            'punta_inicial' => 'nullable|integer',
            'punta_final' => 'nullable|integer',
            'custodia' => 'nullable|integer',

            'es_generico' => 'boolean',
            'nombre_alternativo' => 'nullable|string',

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
            'codigo_activo_fijo' => 'nullable|string',
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
            $detalle = DetalleProducto::where('descripcion', $this->descripcion)
                ->where('serial', $this->serial)
                ->first();

            if (!is_null($detalle)) {
                if (in_array($this->method(), ['POST'])) {
                    if (
                        $detalle->descripcion === strtoupper($this->descripcion) &&
                        strtoupper($this->serial) !== $detalle->serial &&
                        count($this->seriales) < 1
                    ) {
                        $validator->errors()->add('descripcion', 'Ya hay un detalle registrado con la misma descripción');
                    }
                }
            }

            // Validación para varios items
            if ($this->varios_items && is_array($this->seriales) && count($this->seriales) > 0) {

                // Validar que no haya seriales vacíos
                $serialesVacios = collect($this->seriales)->filter(function ($item) {
                    return empty($item['serial']);
                });

                if ($serialesVacios->count() > 0) {
                    $validator->errors()->add('seriales', 'Todos los seriales deben estar completos');
                    return;
                }

                // Validar duplicados en el array
                $seriales = array_column($this->seriales, 'serial');
                $duplicados = array_diff_assoc($seriales, array_unique($seriales));

                if (!empty($duplicados)) {
                    $validator->errors()->add(
                        'seriales',
                        'Tienes números de serie repetidos: ' . implode(', ', array_unique($duplicados))
                    );
                    return;
                }
                //  Validar que cada serial no exista en BD con la misma descripción
                $descripcion = strtoupper($this->descripcion);

                foreach ($this->seriales as $index => $item) {
                    $existe = DetalleProducto::where('descripcion', $descripcion)
                        ->where('serial', $item['serial'])
                        ->exists();

                    if ($existe) {
                        $validator->errors()->add(
                            "seriales.{$index}.serial",
                            "El serial '{$item['serial']}' ya existe con esta descripción en la base de datos"
                        );
                    }
                }
            }
        });
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'descripcion' => strtoupper($this->descripcion)
        ]);

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
