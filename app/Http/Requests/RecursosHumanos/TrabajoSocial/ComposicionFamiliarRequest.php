<?php

namespace App\Http\Requests\RecursosHumanos\TrabajoSocial;

use Illuminate\Foundation\Http\FormRequest;

class ComposicionFamiliarRequest extends FormRequest
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
            'id' => 'required|integer',
            'nombres_apellidos' => 'required|string',
            'parentesco' => 'required|string',
            'edad' => 'required|integer',
            'estado_civil' => 'required|string',
            'instruccion' => 'required|string',
            'ocupacion' => 'required|string',
            'discapacidad' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'ingreso_mensual' => 'required|numeric',
        ];
    }
}
