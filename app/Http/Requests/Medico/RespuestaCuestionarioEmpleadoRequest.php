<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RespuestaCuestionarioEmpleadoRequest extends FormRequest
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
            'cuestionario_id'=> 'nullable|exists:med_cuestionarios,id',
            'empleado_id'=> 'required|exists:empleados,id',
        ];
    }
    protected function prepareForValidation()
    {
        
            $this->merge([
                'empleado_id' =>  Auth::user()->empleado->id
            ]);
    }
}
