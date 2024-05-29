<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class AntecedenteGinecoObstetricoRequest extends FormRequest
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
            'menarquia' => 'required',
            'ciclos'=> 'required',
            'numero_historia_clinica'=> 'required|string',
            'fecha_ultima_menstruacion'=> 'required|string',
            'gestas'=> 'required',
            'partos'=> 'required',
            'cesareas'=> 'required',
            'abortos'=> 'required',
            'antecedentes_personales_id'=> 'required|exists:med_antecedentes_personales,id',

        ];
    }
    protected function prepareForValidation()
    {
            $this->merge([
                'antecedentes_personales_id' => $this->antecedentes_personales,
            ]);
    }
}
