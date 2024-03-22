<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

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
            'rp' => 'required|string',
            'prescripcion' => 'required|string',
            'cita_medica' => 'nullable|numeric|integer|exists:med_citas_medicas,id',
            'registro_empleado_examen' => 'nullable|numeric|integer|exists:med_citas_medicas,id',
            'diagnosticos.*.cie' => 'required|exists:med_cies,id',
            'diagnosticos.*.recomendacion' => 'nullable|string',
        ];
    }

    /*protected function prepareForValidation()
    {
        // Iterar sobre cada entrada de diagnóstico y agregar cita_medica_id
        foreach ($this->diagnosticos as $key => $diagnostico) {
            Log::channel('testing')->info('Log', ['cie 1', $diagnostico['cie']]);
            // Log::channel('testing')->info('Log', ['cie 2', $diagnostico->cie]);
            /*$this->merge([
                "diagnosticos.*.cie" => $diagnostico['cie'],
                "diagnosticos.*.cita_medica_id" => $this->cita_medica,
                "diagnosticos.*.recomendacion" => $diagnostico['recomendacion'],
            ]);
        }
    }*/
}
