<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BitacoraVehicularRequest extends FormRequest
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
        $rules = [
            'fecha' => 'date|required|date_format:Y-m-d',
            'hora_salida' => 'string|required',
            'hora_llegada' => 'string|required',
            'km_inicial' => 'string|required',
            'km_final' => 'string|sometimes|nullable',
            'tanque_inicio' => 'string|required',
            'tanque_final' => 'string|sometimes|nullable',
            'firmada' => 'boolean|sometimes',
            'chofer' => 'sometimes|exists:empleados,id',
            'vehiculo' => 'numeric|exists:vehiculos,id',
        ];

        return $rules;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'fecha' => date('Y-m-d', strtotime($this->fecha)),
            'chofer' => auth()->user()->empleado->id,
            'chofer_id' => auth()->user()->empleado->id
        ]);
    }
}
