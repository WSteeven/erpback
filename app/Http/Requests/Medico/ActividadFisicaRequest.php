<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class ActividadFisicaRequest extends FormRequest
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
            'ficha_ficha_preocupacional_id' => 'required|exists:med_preocupacionales,id',
            'nombre_actividad' => 'required|string',
            'tiempo' => 'required',
        ];
    }
}
