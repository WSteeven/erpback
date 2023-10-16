<?php

namespace App\Http\Requests\Vehiculos;

use Illuminate\Foundation\Http\FormRequest;

class ConductorRequest extends FormRequest
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
            'empleado' => 'required|exists:empleados,id',
            'identificacion' => 'required',
            'tipo_licencia' => 'required',
            'inicio_vigencia' => 'required',
            'fin_vigencia' => 'required',
            'puntos' => 'required',
            'plaza' => 'required',
        ];
    }
}
