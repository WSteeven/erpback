<?php

namespace App\Http\Requests\RecursosHumanos\NominaPrestamos;

use Illuminate\Foundation\Http\FormRequest;

class DetalleVacacionRequest extends FormRequest
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
            'vacacion_id'=>'required|exists:rrhh_nomina_vacaciones,id',
            'fecha_inicio'=>'required|string',
            'fecha_fin'=>'required|string',
            'dias_utilizados'=>'required|numeric',
            'vacacionable_id'=>'sometimes|nullable|integer',
            'vacacionable_type'=>'sometimes|nullable|string',
            'observacion'=> 'required|string',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'vacacion_id'=> $this->vacacion_id ?? $this->vacacion
        ]);
    }
}
