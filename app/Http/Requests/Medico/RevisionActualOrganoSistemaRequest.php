<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class RevisionActualOrganoSistemaRequest extends FormRequest
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
            'organo_sistema_id'=> 'required|exists:med_sistemas_organicos,id',
            'descripcion'=> 'required|string',
            'preocupacional_id'=> 'required|exists:med_preocupacionales,id',
        ];
    }
    protected function prepareForValidation()
    {
            $this->merge([
                'organo_sistema_id' => $this->organo_sistema,
            ]);
    }
}
