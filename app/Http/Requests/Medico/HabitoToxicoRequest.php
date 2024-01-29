<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class HabitoToxicoRequest extends FormRequest
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
            'preocupacional_id' => 'required|exists:med_preocupacionales,id',
            'tipo_habito_toxico_id' => 'required|exists:med_tipos_habitos_toxicos,id',
            'tiempo_consumo' => 'required|string',
        ];
    }
}
