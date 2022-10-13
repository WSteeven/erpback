<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

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
            'condicion'=>'required|integer|exists:condiciones_de_productos,id',
            'cantidad'=>'required|integer',
            // 'detalle'=>['required|integer|exists:detalles_productos,id|unique:inventarios,detalle_id,NULL,sucursal_id'.$this->sucursal,
            'detalle_id'=>['required', Rule::unique('inventarios')->where(function($query) use ($request){
                return $query->where('sucursal_id', $request->sucursal_id)
                ->where('cliente_id', $request->cliente_id)
                ->where('condicion_id', $request->condicion);
            })],
            'sucursal_id'=>'required|integer|exists:sucursales,id|unique:inventarios,detalle_id',
            'cliente_id'=>'required|integer|exists:clientes,id|unique:inventarios,detalle_id',
            'prestados'=>'sometimes|integer',
            //'estado'=>'required|integer',
        ];

        if(in_array($this->method(), ['PUT', 'PATCH'])){
            $inventario = $this->route()->parameter('inventario');
            //Log::channel('testing')->info('Log', ['inventario recibido', $inventario]);

            $rules['detalle_id'] = ['required', Rule::unique('inventarios')->ignore($inventario)->where(function($query) use ($request){
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
            'detalle_id.unique'=>'Ya existe un producto en el inventario para el mismo propietario y en la misma sucursal'
        ];
    }
}
