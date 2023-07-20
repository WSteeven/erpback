<?php

namespace App\Http\Requests;

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
            'fecha_inicio' => 'required|date_format:Y-m-d',
            'fecha_fin' => 'required|date_format:Y-m-d',
            'fecha_inicio_rango1_vacaciones' => 'nullable|date_format:Y-m-d',
            'fecha_fin_rango1_vacaciones' => 'nullable|date_format:Y-m-d',
            'fecha_inicio_rango2_vacaciones' => 'nullable|date_format:Y-m-d',
            'fecha_fin_rango2_vacaciones' => 'nullable|date_format:Y-m-d',
            'solicitud' => 'string|required',
            'descuento_vacaciones' => 'required|integer',
            'numero_dias' => 'required|integer',
            'numero_dias_rango1' => 'nullable|integer',
            'numero_dias_rango2' => 'nullable|integer'
        ];
    }
    protected function prepareForValidation()
    {
        $fecha_inicio = Carbon::createFromFormat('d-m-Y', $this->fecha_inicio);
        $fecha_fin = Carbon::createFromFormat('d-m-Y', $this->fecha_fin);
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
                $date = Carbon::createFromFormat('d-m-Y', $this->$field);
                $this->merge([
                    $field => $date->format('Y-m-d'),
                ]);
            }
        }
        $this->merge([
            'periodo_id' => $this->periodo,
            'fecha_inicio' => $fecha_inicio->format('Y-m-d'),
            'fecha_fin' => $fecha_fin->format('Y-m-d'),
        ]);
    }
}
