<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class AptitudMedicaRequest extends FormRequest
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
            'tipo_aptitud_id'=> 'required|exists:med_tipos_aptitudes,id',
            'presion_arterial'=> 'required',
            'observacion'=> 'required',
            'limitacion'=> 'required',
            'ficha_preocupacional_id'=> 'required|exists:med_fichas_preocupacionales,id',

        ];
    }
}
