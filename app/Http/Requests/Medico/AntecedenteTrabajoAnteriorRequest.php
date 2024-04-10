<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class AntecedenteTrabajoAnteriorRequest extends FormRequest
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
            'empresa' => 'required|string',
            'puesto_trabajo' => 'required|string',
            'actividades_desempenaba' => 'required|string',
            'tiempo_trabajo_meses' => 'required|integer',
            'r_fisico' => 'required|string',
            'r_mecanico' => 'required|string',
            'r_quimico' => 'required|string',
            'r_biologico' => 'required|string',
            'r_ergonomico' => 'required|string',
            'r_phisosocial' => 'required|string',
            'observacion' => 'required|string',
            'ficha_preocupacional_id' => 'required|exists:med_fichas_preocupacionales,id',
        ];
    }
}
