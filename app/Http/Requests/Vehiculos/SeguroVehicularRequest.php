<?php

namespace App\Http\Requests\Vehiculos;

use Illuminate\Foundation\Http\FormRequest;

class SeguroVehicularRequest extends FormRequest
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
            'nombre' => 'string|required',
            'num_poliza' => 'string|required',
            'fecha_caducidad' => 'string|required',
            'estado' => 'boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge(['fecha_caducidad' => date('Y-m-d', strtotime($this->fecha_caducidad))]);
    }
}
