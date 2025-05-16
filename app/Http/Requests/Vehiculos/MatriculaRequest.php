<?php

namespace App\Http\Requests\Vehiculos;

use Illuminate\Foundation\Http\FormRequest;

class MatriculaRequest extends FormRequest
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
            'vehiculo' => 'required|exists:vehiculos,id',
            'fecha_matricula' => 'string|nullable',
            'proxima_matricula' => 'string|nullable',
            'matriculador' => 'string|nullable',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge(['fecha_matricula' => date('Y-m-d', strtotime('01-' . $this->fecha_matricula))]);
        $this->merge(['proxima_matricula' => date('Y-m-d', strtotime('01-' . $this->proxima_matricula))]);
    }
}
