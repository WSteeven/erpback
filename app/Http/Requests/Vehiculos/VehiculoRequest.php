<?php

namespace App\Http\Requests\Vehiculos;

use App\Models\Vehiculos\Vehiculo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehiculoRequest extends FormRequest
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
            'placa' => 'required|string|unique:vehiculos',
            'num_chasis' => 'required|string|unique:vehiculos',
            'num_motor' => 'required|string|unique:vehiculos',
            'anio_fabricacion' => 'required|numeric',
            'cilindraje' => 'required|numeric',
            'rendimiento' => 'sometimes|numeric',
            'modelo' => 'required|exists:modelos,id',
            'combustible' => 'required|exists:combustibles,id',
            'traccion' => ['required', Rule::in([Vehiculo::AWD, Vehiculo::TODOTERRENO, Vehiculo::SENCILLA_DELANTERA, Vehiculo::SENCILLA_TRASERA, Vehiculo::FOUR_WD, Vehiculo::DOSXDOS, Vehiculo::DOSXUNO])],
            'aire_acondicionado' => 'required|boolean',
            'capacidad_tanque' => 'required|numeric',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $vehiculo = $this->route()->parameter('vehiculo');

            $rules['placa'] = ['required', 'string', Rule::unique('vehiculos')->ignore($vehiculo)];
            $rules['num_chasis'] = ['required', 'string', Rule::unique('vehiculos')->ignore($vehiculo)];
            $rules['num_motor'] = ['required', 'string', Rule::unique('vehiculos')->ignore($vehiculo)];
        }

        return $rules;
    }
}
