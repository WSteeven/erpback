<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DetalleProductoTransaccionRequest extends FormRequest
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
            'detalle_id'=>'required|exists:detalles_productos,id',
            'transaccion_id'=>'required|exists:transacciones_bodega,id',
            'cantidad_inicial'=>'required|numeric',
            'cantidad_final'=>'required|numeric',
        ];
    }
}
