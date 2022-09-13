<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PisoRequest extends FormRequest
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
            'fila' => 'unique:pisos,fila,NULL,id,columna,' . $this->columna,
            'columna' => 'required|string'
        ];
    }
    
    public function messages()
    {
        return [
            'piso.unique'=>'Ya existe el piso y columna que intentas ingresar'
        ];
    }
}
