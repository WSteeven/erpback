<?php

namespace App\Http\Requests\ControlPersonal;

use Illuminate\Foundation\Http\FormRequest;

class OficinaBiometricoRequest extends FormRequest
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
            'nombre'=>'required|string|max:255',
            'descripcion'=>'sometimes|nullable|string',
            'direccion'=>'sometimes|nullable|string',
            'latitud'=>'sometimes|nullable|numeric|between:-90,90',
            'longitud'=>'sometimes|nullable|numeric|between:-180,180',
            'direccion_ip'=>'sometimes|nullable|ip',
            'puerto'=>'sometimes|nullable|integer|min:1|max:65535', // opcional
            'canton_id'=>'required|exists:cantones,id',
            'activo'=>'boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
           'canton_id'=>$this->canton
        ]);
    }
}
