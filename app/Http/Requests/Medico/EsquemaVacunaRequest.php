<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class EsquemaVacunaRequest extends FormRequest
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
            'dosis_aplicadas' => 'required|numeric|integer',
            'observacion' => 'nullable|string',
            'fecha' => 'required|string',
            'lote' => 'nullable|string',
            'responsable_vacunacion' => 'nullable|string',
            'establecimiento_salud' => 'nullable|string',
            'es_dosis_unica' => 'boolean',
            'fecha_caducidad' => 'nullable|string',
            'tipo_vacuna_id' => 'required|numeric|integer|exists:med_tipos_vacunas,id',
            'paciente_id' => 'required|numeric|integer|exists:empleados,id',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'tipo_vacuna_id' => $this->tipo_vacuna,
            'paciente_id' => $this->paciente,
        ]);
    }
}
