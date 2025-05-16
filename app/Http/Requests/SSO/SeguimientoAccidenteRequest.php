<?php

namespace App\Http\Requests\SSO;

use Illuminate\Foundation\Http\FormRequest;

class SeguimientoAccidenteRequest extends FormRequest
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
            'condiciones_climatologicas' => 'nullable|string',
            'condiciones_laborales' => 'nullable|string',
            'autorizaciones_permisos_texto' => 'nullable|string',
            'autorizaciones_permisos_foto' => 'nullable|string',
            'se_notifica_riesgos_trabajo' => 'boolean',
            'actividades_desarrolladas' => 'nullable|string',
            'descripcion_amplia_accidente' => 'nullable|string',
            'metodologia_utilizada' => 'nullable|string',
            'antes_accidente' => 'nullable|string',
            'instantes_previos' => 'nullable|string',
            'durante_accidente' => 'nullable|string',
            'despues_accidente' => 'nullable|string',
            'hipotesis_causa_accidente' => 'nullable|string',
            'causas_inmediatas' => 'nullable|string',
            'causas_basicas' => 'nullable|string',
            'medidas_preventivas' => 'nullable|string',
            'seguimiento_sso' => 'nullable|string',
            'seguimiento_trabajo_social' => 'nullable|string',
            'seguimiento_rrhh' => 'nullable|string',
            'subtarea_id' => 'nullable|numeric|integer|exists:subtareas,id',
            'accidente_id' => 'nullable|numeric|integer|exists:sso_accidentes,id',
        ];

        if ($this->isMethod('patch')) {
            $rules = collect($rules)->only(array_keys($this->all()))->toArray(); // Esta regla está bien para pach, verificado el 14/8/2024
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        $map = [
            'subtarea' => 'subtarea_id',
            'accidente' => 'accidente_id',
        ];

        foreach ($map as $inputField => $dbField) {
            if ($this->has($inputField)) {
                $this->merge([$dbField => $this[$inputField]]);
            }
        }
    }
}
