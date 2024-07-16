<?php

namespace App\Http\Requests\Vehiculos;

use Illuminate\Foundation\Http\FormRequest;

class RegistroIncidenteRequest extends FormRequest
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
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'fecha' => 'required|date:Y-m-d',
            'descripcion' => 'required|string',
            'tipo' => 'required|string',
            'gravedad' => 'required|string',
            'persona_reporta_id' => 'required|exists:empleados,id',
            'persona_registra_id' => 'required|exists:empleados,id',
            'aplica_seguro' => 'boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'vehiculo_id' => $this->vehiculo,
            'persona_reporta_id' => $this->persona_reporta,
            'persona_registra_id' => $this->persona_registra,
        ]);
        if (is_null($this->persona_registra)) $this->merge(['persona_registra_id' => auth()->user()->empleado->id]);
    }
}
