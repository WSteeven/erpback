<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class AntecedenteFamiliarRequest extends FormRequest
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
            'tipo_antecedente_familiar_id'=> 'required|exists:med_tipos_antecedentes_familiares,id',
            'ficha_preocupacional_id'=> 'required|exists:med_fichas_preocupacionales,id',
        ];
    }
}
