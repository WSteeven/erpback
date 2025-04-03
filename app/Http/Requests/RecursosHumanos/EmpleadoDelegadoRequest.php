<?php

namespace App\Http\Requests\RecursosHumanos;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class EmpleadoDelegadoRequest extends FormRequest
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
            'delegado_id' => 'required|exists:empleados,id',
            'fecha_hora_desde' => 'nullable|string',
            'fecha_hora_hasta' => 'required|string',
            'activo' => 'boolean'
        ];
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (Carbon::parse($this->fecha_hora_hasta) > Carbon::now()) $validator->errors()->add('fecha_hora_hasta', 'La fecha y hora que finaliza la delegación debe ser mayor a la fecha y hora actual.');
            if ($this->fecha_hora_desde)
                if (Carbon::parse($this->fecha_hora_desde) > Carbon::parse($this->fecha_hora_hasta)) $validator->errors()->add('fecha_hora_desde', 'La fecha y hora de finalización no debe ser mayor a la fecha y hora inicial.');
        });
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'empleado_id' => $this->empleado ?: auth()->user()->empleado->id,
            'delegado_id' => $this->delegado,
            'fecha_hora_desde' => $this->fecha_hora_desde ?: Carbon::now()->addMinute()
        ]);
    }
}
