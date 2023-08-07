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
            'fecha_inicio_rango1_vacaciones' => 'required|date_format:Y-m-d',
            'fecha_fin_rango1_vacaciones' => 'required|date_format:Y-m-d',
            'fecha_inicio_rango2_vacaciones' => 'required|date_format:Y-m-d',
            'fecha_fin_rango2_vacaciones' => 'required|date_format:Y-m-d',
            'solicitud' => 'string|required'

        ];
    }
    protected function prepareForValidation()
    {
        $fecha_inicio_rango1_vacaciones = Carbon::createFromFormat('d-m-Y', $this->fecha_inicio_rango1_vacaciones);
        $fecha_fin_rango1_vacaciones = Carbon::createFromFormat('d-m-Y', $this->fecha_fin_rango1_vacaciones);
        $fecha_inicio_rango2_vacaciones = Carbon::createFromFormat('d-m-Y', $this->fecha_inicio_rango2_vacaciones);
        $fecha_fin_rango2_vacaciones = Carbon::createFromFormat('d-m-Y', $this->fecha_fin_rango2_vacaciones);
        if (is_null($this->empleado)) {
            $empleado = Auth::user()->empleado->id;
            $this->merge([
                'empleado_id' => $empleado,
            ]);
        }else{
            $this->merge([
                'empleado_id' => $this->empleado,
            ]);
        }
        $this->merge([
            'periodo_id' => $this->periodo,
            'fecha_inicio_rango1_vacaciones' => $fecha_inicio_rango1_vacaciones->format('Y-m-d'),
            'fecha_fin_rango1_vacaciones' => $fecha_fin_rango1_vacaciones->format('Y-m-d'),
            'fecha_inicio_rango2_vacaciones' => $fecha_inicio_rango2_vacaciones->format('Y-m-d'),
            'fecha_fin_rango2_vacaciones' => $fecha_fin_rango2_vacaciones->format('Y-m-d'),
        ]);
    }
}
