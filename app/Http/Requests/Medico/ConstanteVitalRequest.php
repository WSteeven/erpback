<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class ConstanteVitalRequest extends FormRequest
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
            'presion_arterial'=> 'required',
            'temperatura'=> 'required',
            'frecuencia_cardiaca'=> 'required',
            'saturacion_oxigeno'=> 'required',
            'frecuencia_respiratoria'=> 'required',
            'peso'=> 'required',
            'estatura'=> 'required',
            'talla'=> 'required',
            'indice_masa_corporal'=> 'required',
            'perimetro_abdominal'=> 'required',
            'preocupacional_id'=> 'required|exists:med_preocupacionales,id',
        ];

    }
}
