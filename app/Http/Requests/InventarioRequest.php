<?php

namespace App\Http\Requests;

use App\Models\DetalleProducto;
use App\Models\Inventario;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class InventarioRequest extends FormRequest
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
        $request = $this;
        $rules = [
            'condicion' => 'required|integer|exists:condiciones_de_productos,id',
            'cantidad' => 'required|integer',
            'detalle_id' => 'required|integer|exists:detalles_productos,id', //|unique:inventarios,detalle_id,NULL,sucursal_id'.$this->sucursal,
            /* 'detalle_id' => ['required', Rule::unique('inventarios')->where(function ($query) use ($request) {
                return $query->where('sucursal_id', $request->sucursal_id)
                    ->where('cliente_id', $request->cliente_id)
                    ->where('condicion_id', $request->condicion);
            })], */
            'sucursal_id' => 'required|integer|exists:sucursales,id', //|unique:inventarios,detalle_id',
            'cliente_id' => 'required|integer|exists:clientes,id', //|unique:inventarios,detalle_id',
            // 'prestados' => 'sometimes|integer',
            'por_recibir' => 'sometimes|integer',
            'por_entregar' => 'sometimes|integer',
            // 'estado' => Rule::in([Inventario::INVENTARIO, Inventario::SIN_STOCK, Inventario::TRANSITO]),
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $inventario = $this->route()->parameter('inventario');
            Log::channel('testing')->info('Log', ['inventario recibido', $inventario]);

            $rules['detalle_id'] = ['required', Rule::unique('inventarios')->ignore($inventario)->where(function ($query) use ($request) {
                return $query->where('sucursal_id', $request->sucursal_id)
                    ->where('cliente_id', $request->cliente_id)
                    ->where('condicion_id', $request->condicion);
            })];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'detalle_id.unique' => 'Ya existe un producto en el inventario para el mismo propietario y en la misma sucursal'
        ];
    }

    public function withValidator($validator)
    {

        $validator->after(function ($validator) {
            $detalle = DetalleProducto::find($this->detalle_id);
            if ($detalle->serial) {
                if ($this->cantidad > 1) {
                    $validator->errors()->add('detalle_id', 'Este producto es único, no se puede registrar más de una unidad');
                }

                $inventario = Inventario::where('detalle_id', $this->detalle_id)->get();
                Log::channel('testing')->info('Log', ['Detalle', $this->detalle_id, 'inventario', $inventario]);
                if (!$inventario->isEmpty()) {
                    if (in_array($this->method(), ['POST'])) {
                        $validator->errors()->add('detalle_id', 'Este producto ya consta en el sistema. Si desea actualizar la condición por favor modifique el producto');
                    }
                }
            }
        });
    }

    protected function prepareForValidation()
    {
        // if (is_null($this->prestados)) $this->merge(['prestados' => 0]);

        is_null($this->por_recibir) ?? $this->merge(['por_recibir' => 0]);
        is_null($this->por_entregar) ?? $this->merge(['por_entregar' => 0]);
        is_null($this->estado) ?? $this->merge(['estado' =>Inventario::INVENTARIO]);
    }
}
