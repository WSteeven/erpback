<?php

namespace App\Http\Requests\RecursosHumanos\Alimentacion;

use Illuminate\Foundation\Http\FormRequest;

class AsignarAlimentacionRequest extends FormRequest
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
            'empleados' => 'nullable',
            'empleado' => 'nullable',
            'valor_minimo' => 'required',
        ];
    }
}
