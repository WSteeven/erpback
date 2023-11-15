<?php

namespace App\Http\Requests\Vehiculos;

use Illuminate\Foundation\Http\FormRequest;

class MultaConductorRequest extends FormRequest
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
        $rules =  [
            'empleado' => 'required|exists:veh_conductores,empleado_id',
            'fecha_infraccion' => 'required|string',
            'placa' => 'nullable|sometimes',
            'puntos' => 'string|sometimes',
            'total' => 'numeric|required',
            'estado' => 'sometimes|boolean',
            'fecha_pago' => 'string|sometimes|nullable',
            'comentario' => 'string|sometimes|nullable',
        ];
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['empleado'] = ['required'];
        }
        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->merge(['fecha_infraccion' => date('Y-m-d', strtotime($this->fecha_infraccion))]);
        $this->merge(['fecha_pago' => date('Y-m-d', strtotime($this->fecha_pago))]);
    }
}
