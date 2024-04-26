<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class ResultadoExamenRequest extends FormRequest
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
            '*.id' => 'nullable|numeric|integer',
            '*.resultado' => 'required|numeric',
            '*.observaciones' => 'nullable|string',
            '*.configuracion_examen_campo' => 'required|exists:med_configuraciones_examenes_campos,id',
            '*.examen_solicitado' => 'required|exists:med_examenes_solicitados,id',
        ];
    }
}
