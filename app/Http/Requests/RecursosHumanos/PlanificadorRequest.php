<?php

namespace App\Http\Requests\RecursosHumanos;

use Illuminate\Foundation\Http\FormRequest;

class PlanificadorRequest extends FormRequest
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
            'completado' => 'sometimes|nullable|numeric',
            'actividades' => 'required|array',
            'actividades.*.id' => 'required|integer',
            'actividades.*.nombre' => 'required|string',
            'actividades.*.completado' => 'required|numeric',
            'actividades.*.subactividades' => 'required|array',
            'actividades.*.subactividades.*.actividad_id' => 'required',
            'actividades.*.subactividades.*.nombre' => 'required|string',
            'actividades.*.subactividades.*.responsable' => 'required|exists:empleados,id',
            'actividades.*.subactividades.*.fecha_inicio' => 'required|string',
            'actividades.*.subactividades.*.fecha_fin' => 'required|string',
            'actividades.*.subactividades.*.estado_avance' => 'required|string',
            'actividades.*.subactividades.*.periodicidad' => 'required|string',
            'actividades.*.subactividades.*.observaciones' => 'sometimes|nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge(['empleado_id' => $this->empleado,
            'completado' => $this->completado ?: 0]);
    }
}
