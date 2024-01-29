<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class FactorRiesgoRequest extends FormRequest
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
            'tipo_factor_riesgo_id'=> 'required|exists:med_tipos_factores_riesgos,id',
            'categoria_factor_riesgo_id'=> 'required|exists:med_categorias_factores_riesgos,id',
            'preocupacional_id'=> 'required|exists:med_preocupacionales,id',
        ];
    }
    protected function prepareForValidation()
    {
            $this->merge([
                'tipo_factor_riesgo_id' => $this->tipo_factor_riesgo,
                'categoria_factor_riesgo_id' => $this->categoria_factor_riesgo
            ]);
    }
}
