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
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'producto_id'=>'required|integer',
            'condicion_id'=>'required|integer',
            'ubicacion_id'=>'required|integer',
            'propietario_id'=>'required|integer',
            'stock'=>'required|integer',
            'prestados'=>'required|integer',
            'estado'=>'required|integer',
        ];
    }
}
