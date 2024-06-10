<?php

namespace App\Http\Requests\RecursosHumanos\NominaPrestamos;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class VacacionRequest extends FormRequest
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
            'periodo_id' => 'required|exists:periodos,id',
            'derecho_vacaciones' => 'nullable|date_format:Y-m-d',
            'fecha_inicio' => 'nullable|date_format:Y-m-d',
            'fecha_fin' => 'nullable|date_format:Y-m-d',
            'fecha_inicio_rango1_vacaciones' => 'nullable|date_format:Y-m-d',
            'fecha_fin_rango1_vacaciones' => 'nullable|date_format:Y-m-d',
            'fecha_inicio_rango2_vacaciones' => 'nullable|date_format:Y-m-d',
            'fecha_fin_rango2_vacaciones' => 'nullable|date_format:Y-m-d',
            'descuento_vacaciones' => 'required|integer',
            'numero_dias' => 'nullable|integer',
            'numero_dias_rango1' => 'nullable|integer',
            'numero_dias_rango2' => 'nullable|integer'
        ];
    }
    protected function prepareForValidation()
    {
        $empleado_id = $this->empleado ?? Auth::user()->empleado->id;
        $this->merge([
            'empleado_id' => $empleado_id,
        ]);
        $dateFields = [
            'fecha_inicio_rango1_vacaciones',
            'fecha_fin_rango1_vacaciones',
            'fecha_inicio_rango2_vacaciones',
            'fecha_fin_rango2_vacaciones',
            'derecho_vacaciones'
        ];
        foreach ($dateFields as $field) {
            if ($this->$field) {
                $this->merge([
                    $field =>$this->$field,
                ]);
            }
        }
        $this->merge([
            'periodo_id' => $this->periodo,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' =>  is_null($this->fecha_fin) ? $this->fecha_fin_rango2_vacaciones :$this->fecha_fin,
            'descuento_vacaciones' => $this->descuento_vacaciones?$this->descuento_vacaciones:0
        ]);
    }
}
