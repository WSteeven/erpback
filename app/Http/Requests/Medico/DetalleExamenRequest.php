<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class DetalleExamenRequest extends FormRequest
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

            'tipo_examen_id' => 'required|exists:med_tipos_examenes,id',
            'categoria_examen_id' => 'required|exists:med_categorias_examenes,id',
            'examen_id' => 'required|exists:med_examenes,id',
        ];
    }
    protected function prepareForValidation()
    {
            $this->merge([
                'tipo_examen_id' => $this->tipo_examen,
                'categoria_examen_id' => $this->categoria_examen,
                'examen_id' => $this->examen
            ]);
    }
}
