<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class PermisoEmpleadoRequest extends FormRequest
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
            'motivo' => 'required',
            'fecha_inicio' => 'required|date_format:Y-m-d',
            'fecha_fin' => 'required|date_format:Y-m-d',
            'justificacion' => 'required|string',
            'empleado_id' => 'required|exists:empleados,id',
        ];
    }
    protected function prepareForValidation()
    {
        $fecha_inicio = Carbon::createFromFormat('d-m-Y', $this->fecha_inicio);
        $fecha_fin = Carbon::createFromFormat('d-m-Y', $this->fecha_fin);
        //usuario logueado
        $this->merge([
            'empleado_id' => auth()->user()->empleado->id,
        ]);
        $this->merge([
            'fecha_inicio' =>  $fecha_inicio->format('Y-m-d'),
        ]);
        $this->merge([
            'fecha_fin' =>  $fecha_fin->format('Y-m-d'),
        ]);

    }
}
