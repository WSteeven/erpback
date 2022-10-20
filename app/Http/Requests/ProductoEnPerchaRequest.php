<?php

namespace App\Http\Requests;

use App\Models\Inventario;
use App\Models\ProductoEnPercha;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class ProductoEnPerchaRequest extends FormRequest
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
            'ubicacion' => 'required|exists:ubicaciones,id|unique:productos_en_perchas,ubicacion_id,NULL,id,inventario_id,' . $this->inventario,
            'inventario' => 'required|exists:inventarios,id|unique:productos_en_perchas,ubicacion_id',
            'stock' => 'required|integer|min:1',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $inventario = Inventario::where('id', $this->inventario)->first();
            Log::channel('testing')->info('Log', ['control de inventario', ProductoEnPercha::controlarCantidadInventario($this->inventario)]);
            Log::channel('testing')->info('Log', ['cantidad en el inventario', $inventario->cantidad]);
            if ((ProductoEnPercha::controlarCantidadInventario($this->inventario) + $this->stock) > $inventario->cantidad) {
                $validator->errors()->add('stock', [
                    'La cantidad que intentas ingresar no debe superar la cantidad existente en el inventario.',
                    // 'Cantidad de inventario: ' . $inventario->cantidad . '. Cantidad en perchas: ' . ProductoEnPercha::controlarCantidadInventario($this->inventario) < 0 ? 0 : ProductoEnPercha::controlarCantidadInventario($this->inventario)
                ]);
                $cantidad = ProductoEnPercha::controlarCantidadInventario($this->inventario);
                if ($cantidad < 0) {
                    $validator->errors()->add('stock', 'Cantidad de inventario: ' . $inventario->cantidad . '. Cantidad en perchas: 0');
                }else{
                    $validator->errors()->add('stock', 'Cantidad de inventario: ' . $inventario->cantidad . '. Cantidad en perchas: ' .$cantidad);
                }
            }
        });
    }

    public function messages()
    {
        return [
            'ubicacion.unique' => 'Ya existe un registro para esta ubicación, intenta hacer una actualización de dicho registro'
        ];
    }
}
