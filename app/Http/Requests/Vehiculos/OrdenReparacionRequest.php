<?php

namespace App\Http\Requests\Vehiculos;

use Illuminate\Foundation\Http\FormRequest;

class OrdenReparacionRequest extends FormRequest
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
            'solicitante_id' => 'required',
            'autorizador_id' => 'required',
            'autorizacion_id' => 'required',
            'vehiculo_id' => 'required',
            'servicios' => 'required',
        ];
    }

    public function prepareForValidation(){
        $this->merge([
'autorizador_id'
'autorizacion_id'
'vehiculo_id'=>1,
        ]);
    }
}
