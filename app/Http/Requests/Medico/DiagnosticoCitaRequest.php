<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class DiagnosticoCitaRequest extends FormRequest
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
            'recomendacion' => 'required|string',
            'cie_id' => 'required|exists:med_cies,id',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'cie_id' => $this->cie,
        ]);
    }
}
