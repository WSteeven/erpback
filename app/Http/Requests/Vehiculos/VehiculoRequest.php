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
            'modelo_id' => 'required|exists:modelos,id',
            'combustible_id' => 'required|exists:combustibles,id',
            'traccion' => ['required', Rule::in([Vehiculo::AWD, Vehiculo::TODOTERRENO, Vehiculo::SENCILLA_DELANTERA, Vehiculo::SENCILLA_TRASERA, Vehiculo::FOUR_WD, Vehiculo::DOSXDOS, Vehiculo::DOSXUNO])],
            'color' => 'required|string',
            'aire_acondicionado' => 'required|boolean',
            'capacidad_tanque' => 'required|numeric',
            'tipo_vehiculo_id' => 'required|exists:veh_tipos_vehiculos,id',
            'tiene_gravamen' => 'boolean',
            'prendador' => 'required_if:tiene_gravamen,true',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $vehiculo = $this->route()->parameter('vehiculo');

            $rules['placa'] = ['required', 'string', Rule::unique('vehiculos')->ignore($vehiculo)];
            $rules['num_chasis'] = ['required', 'string', Rule::unique('vehiculos')->ignore($vehiculo)];
            $rules['num_motor'] = ['required', 'string', Rule::unique('vehiculos')->ignore($vehiculo)];
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        //se castea las llaves foraneas
        $this->merge([
            'modelo_id' => $this->modelo,
            'combustible_id' => $this->combustible,
            'tipo_vehiculo_id' => $this->tipo_vehiculo,
        ]);
    }
}
