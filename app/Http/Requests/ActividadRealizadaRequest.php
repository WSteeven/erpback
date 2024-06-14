<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActividadRealizadaRequest extends FormRequest
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
            'fecha_hora' => 'required|string',
            'actividad' => 'required|string',
            'observacion' => 'nullable|string',
            'fotografia' => 'nullable|string',
            'empleado_id' => 'required|numeric|integer|exists:empleados,id',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge(['empleado_id' => auth()->user()->empleado->id]);
    }
}
