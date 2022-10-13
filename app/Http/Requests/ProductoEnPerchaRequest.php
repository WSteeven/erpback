<?php

namespace App\Http\Requests;

use App\Models\ProductoEnPercha;
use Illuminate\Foundation\Http\FormRequest;

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
            'ubicacion'=>'required|exists:ubicaciones,id|unique:productos_en_perchas,ubicacion_id,NULL,id,inventario_id,'.$this->inventario,
            'inventario'=>'required|exists:inventarios,id|unique:productos_en_perchas,ubicacion_id',
            'stock'=>'required|integer',
        ];
    }

    public function withValidator($validator){
        $validator->after(function($validator){
            if(ProductoEnPercha::controlarCantidadInventario($this->inventario)>$this->inventario->){
                $validator->errors()->add('stock', 'La cantidad que intentas ingresar no debe superar la cantidad existente en el inventario');
            }
        });
    }

    public function messages()
    {
        return [
            'ubicacion.unique'=>'Ya existe un registro para esta ubicación, intenta hacer una actualización de dicho registro'
        ];

    }
}
