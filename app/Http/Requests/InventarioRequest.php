<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'detalle'=>'required|integer|exists:detalles_productos,id',
            'condicion'=>'required|integer|exists:condiciones_de_productos,id',
            'sucursal'=>'required|integer|exists:sucursales,id',
            'cliente'=>'required|integer|exists:clientes,id',
            'cantidad'=>'required|integer',
            //'prestados'=>'sometimes|integer',
            //'estado'=>'required|integer',
        ];
    }
}
