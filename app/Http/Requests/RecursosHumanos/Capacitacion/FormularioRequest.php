<?php

namespace App\Http\Requests\RecursosHumanos\Capacitacion;

use App\Models\RecursosHumanos\Capacitacion\Formulario;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FormularioRequest extends FormRequest
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
            'empleado_id' => 'required|exists:empleados,id',
            'nombre' => 'required|string',
            'formulario' => 'required|array',
            'es_recurrente' => 'boolean',
            'periodo_recurrencia' => 'nullable|integer', //expresado en meses
            'fecha_inicio' => 'nullable|string',
            'tipo' => ['required', Rule::in(Formulario::INTERNO, Formulario::EXTERNO)], //interna,externa
            'activo' => 'boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'formulario.*.valor' => null,
        ]);
    }
}
