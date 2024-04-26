<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class DetalleResultadoExamenRequest extends FormRequest
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
            'estado_solicitud_examen' => 'required|numeric|integer|exists:med_estados_solicitudes_examenes,id',
            'resultados_examenes.*.id' => 'nullable|numeric|integer|exists:med_resultados_examenes,id',
            'resultados_examenes.*.resultado' => 'required|numeric',
            'resultados_examenes.*.configuracion_examen_campo' => 'required|numeric|integer|exists:med_configuraciones_examenes_campos,id',
        ];

        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'estado_solicitud_examen_id' =>  $this->estado_solicitud_examen,
        ]);
    }
}
