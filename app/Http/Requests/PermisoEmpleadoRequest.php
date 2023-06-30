<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

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
            'tipo_permiso' => 'required',
            'fecha_hora_inicio' => 'required|string',
            'fecha_hora_fin' => 'required|string',
            'fecha_recuperacion' => 'nullable|date_format:Y-m-d',
            'hora_recuperacion' => 'nullable|string',
            'justificacion' => 'required|string',
            'empleado_id' => 'required|exists:empleados,id',
        ];
    }
    protected function prepareForValidation()
    {
        $fecha_inicio = Carbon::createFromFormat('d-m-Y H:i', $this->fecha_hora_inicio);
        $fecha_fin = Carbon::createFromFormat('d-m-Y H:i', $this->fecha_hora_fin);
        if($this->fecha_recuperacion !=null) {
        $fecha_recuperacion = Carbon::createFromFormat('d-m-Y',$this->fecha_recuperacion);
        }
        //usuario logueado
        $this->merge([
            'empleado_id' => auth()->user()->empleado->id,
        ]);
        $this->merge([
            'fecha_hora_inicio' =>  $fecha_inicio->format('Y-m-d H:i:s'),
            'fecha_hora_fin' =>  $fecha_fin->format('Y-m-d H:i:s'),
        ]);
        if($this->fecha_recuperacion !=null) {
            $this->merge([
                'fecha_recuperacion' => $fecha_recuperacion->format('Y-m-d'),
            ]);
        }


    }
}
