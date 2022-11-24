<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MotivoRequest extends FormRequest
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
            'nombre'=>'required|string|unique:motivos,nombre,NULL,id,tipo_transaccion_id,'.$this->tipo_transaccion,
            'tipo_transaccion'=>'required|exists:tipos_transacciones,id'
        ];
    }
}
