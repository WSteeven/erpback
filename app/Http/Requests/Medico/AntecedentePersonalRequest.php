<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class AntecedentePersonalRequest extends FormRequest
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
            'antecedentes_quirorgicos' => 'required|string',
            'vida_sexual_activa'=> 'required',
            'tiene_metodo_planificacion_familiar'=> 'required',
            'tipo_metodo_planificacion_familiar'=> 'required|string',
            'hijos_vivos'=> 'required',
            'hijos_muertos'=> 'required',
            'ficha_preocupacional_id'=> 'required|exists:med_preocupacionales,id',
        ];
    }
    protected function prepareForValidation()
    {
            $this->merge([
                'ficha_preocupacional_id' => $this->preocupacional,
            ]);
    }
}
