<?php

namespace App\Http\Requests\Seguridad;

use Illuminate\Foundation\Http\FormRequest;

class BitacoraRequest extends FormRequest
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
            'fecha_hora_inicio_turno' => 'nullable', //required|string',
            'fecha_hora_fin_turno' => 'nullable|string',
            'jornada' => 'required|string',
            'observaciones' => 'nullable|string',
            'prendas_recibidas_ids' => 'required|string',
            'zona_id' => 'required|numeric|integer|exists:seg_zonas,id',
            'agente_turno_id' => 'required|numeric|integer|exists:empleados,id',
            'protector_id' => 'required|numeric|integer|exists:empleados,id',
            'conductor_id' => 'required|numeric|integer|exists:empleados,id',
            'revisado_por_supervisor' => 'nullable|boolean',
            'retroalimentacion_supervisor' => 'nullable|string|max:1000',

        ];

        if ($this->isMethod('patch')) {
            $rules = collect($rules)->only(array_keys($this->all()))->toArray(); // Esta regla está bien para pach, verificado el 14/8/2024
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        if ($this->isMethod('post')) {
            $this->merge([
                'zona_id' => $this['zona'],
                'agente_turno_id' => $this['agente_turno'],
                'protector_id' => $this['protector'],
                'conductor_id' => $this['conductor'],
                'prendas_recibidas_ids' => json_encode($this['prendas_recibidas']),
            ]);
        }
    }
}
