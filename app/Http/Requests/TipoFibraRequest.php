<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TipoFibraRequest extends FormRequest
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
            'nombre' => 'required|unique:tipo_fibras'
        ];
    }
    public function attributes()
    {
        return [
            'nombre'=>'tipo de fibra'
        ];
    }
}
