<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class DiagnosticoRecetaRequest extends FormRequest
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
            'recomendacion' => 'required|string',
            'rp' => 'required|string',
            'prescripcion' => 'required|string',
            'cita_medica' => 'required|numeric|integer|exists:med_citas_medicas,id',
            'diagnosticos.*.cie' => 'required|exists:med_cies,id',
            'diagnosticos.*.recomendacion' => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        // Iterar sobre cada entrada de diagnóstico y agregar cita_medica_id
        foreach ($this->diagnosticos as $key => $diagnostico) {
            $this->merge([
                "diagnosticos.$key.cie_id" => $diagnostico->cie,
                "diagnosticos.$key.cita_medica_id" => $this->cita_medica,
            ]);
        }
    }
}
