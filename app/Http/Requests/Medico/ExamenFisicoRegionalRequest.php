<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class ExamenFisicoRegionalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'categoria_examen_fisico_id'=> 'required|exists:med_tipos_factores_riesgos,id',
            'preocupacional_id'=> 'required|exists:med_preocupacionales,id',
        ];
    }
    protected function prepareForValidation()
    {
            $this->merge([
                'categoria_examen_fisico_id' => $this->categoria_examen_fisico,
            ]);
    }
}
