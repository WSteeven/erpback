<?php

namespace App\Http\Requests\Medico;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class FichaAptitudRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'fecha_emision' => 'required|date_format:Y-m-d',
            'observaciones_aptitud_medica' => 'required|string',
            'recomendaciones' => 'required|string',
            'tipo_evaluacion_id' => 'required|exists:med_tipos_evaluaciones,id',
            'tipo_aptitud_medica_laboral_id' => 'required|exists:med_tipos_aptitudes_medica_laborales,id',
            'tipo_evaluacion_medica_retiro_id' => 'nullable|exists:med_tipos_evaluaciones_medica_retiros,id',
            'preocupacional_id' => 'required|exists:med_preocupacionales,id',
            'nombres' => 'required|string',
            'apellidos' => 'required|string',
            'codigo' => 'required|string',
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'fecha_emision' => Carbon::parse($this->fecha_emision)->format('Y-m-d'),
            'tipo_evaluacion_id' => $this->tipo_evaluacion,
            'tipo_aptitud_medica_laboral_id' => $this->tipo_aptitud_medica_laboral,
        ]);
        if ($this->tipo_evaluacion_medica_retiro !== null) {
            $this->merge([
                'tipo_evaluacion_medica_retiro_id' => $this->tipo_evaluacion_medica_retiro
            ]);
        }
    }
}
