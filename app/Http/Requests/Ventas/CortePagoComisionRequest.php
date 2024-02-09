<?php

namespace App\Http\Requests\Ventas;

use App\Models\EstadoTransaccion;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class CortePagoComisionRequest extends FormRequest
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
            'nombre' => 'required',
            'fecha_inicio' => 'required',
            'fecha_fin' => 'required',
            'estado' => 'required',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $fecha_inicio  = Carbon::parse($this->fecha_inicio);
            $fecha_fin  = Carbon::parse($this->fecha_fin);
            if ($fecha_inicio->month !== $fecha_fin->month) $validator->errors()->add('fecha_fin', 'La fecha de fin debe corresponder al mismo mes que la fecha de inicio. Solo se puede hacer cortes de un mismo mes');
        });
    }

    protected function prepareForValidation()
    {
        if (is_null($this->estado)) $this->merge(['estado' => EstadoTransaccion::PENDIENTE]);
    }
}
