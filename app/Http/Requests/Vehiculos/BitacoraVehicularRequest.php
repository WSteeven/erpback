<?php

namespace App\Http\Requests\Vehiculos;

use App\Models\Vehiculos\Vehiculo;
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
            'hora_llegada' => 'nullable|string|sometimes',
            'km_inicial' => 'string|required',
            'km_final' => 'string|sometimes|nullable',
            'tanque_inicio' => 'numeric|required',
            'tanque_final' => 'numeric|sometimes|nullable',
            'firmada' => 'boolean|sometimes',
            'chofer_id' => 'sometimes|exists:empleados,id',
            'vehiculo_id' => 'numeric|exists:vehiculos,id',
        ];

        return $rules;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'fecha' => date('Y-m-d', strtotime($this->fecha)),
            'vehiculo_id'=>Vehiculo::where('placa', $this->vehiculo)->first()?->id,
        ]);
    }
}
