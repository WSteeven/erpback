<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class DescripcionAntecedenteTrabajoRequest extends FormRequest
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
            'calificado_iess' => 'required',
            'descripcion' => 'required|string',
            'fecha' => 'required|string',
            'observacion' => 'required|string',
            'tipo_descripcion_antecedente_trabajo' => 'required|string',
            'ficha_preocupacional_id' => 'required|exists:med_preocupacionales,id',
        ];
    }
}
