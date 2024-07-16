<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class ConfiguracionExamenCategoriaRequest extends FormRequest
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
            'nombre' => 'required|string',
            'examen_id' => 'required|exists:med_examenes,id',
        ];
    }
    protected function prepareForValidation()
    {
            $this->merge([
                'examen_id' => $this->examen
            ]);
    }
}
