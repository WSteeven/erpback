<?php

namespace App\Http\Requests\RecursosHumanos;

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
            'opto_pago' => 'boolean',
            'observacion' => 'nullable|string',
            'mes_pago' => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'empleado_id' => $this->empleado ?? Auth::user()->empleado->id,
            'periodo_id' => $this->periodo,
        ]);
    }
}
