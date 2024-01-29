<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class ExamenPreocupacionalRequest extends FormRequest
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
            'nombre' => 'required|string',
            'tiempo' => 'required',
            'resultados' => 'required|string',
            'genero' => 'required|string',
            'antecedente_personal_id' => 'required',
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'antecedente_personal_id' => $this->antecedente_personal,
        ]);
    }
}
