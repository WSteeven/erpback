<?php

namespace App\Http\Requests\RecursosHumanos\TrabajoSocial;

use Illuminate\Foundation\Http\FormRequest;

class SaludRequest extends FormRequest
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
            'tiene_discapacidad' => 'boolean',
            'discapacidades' => 'required_if_accepted:tiene_discapacidad|array',
            'discapacidades.*.tipo_discapacidad' => 'required_if_accepted:tiene_discapacidad|string',
            'discapacidades.*.porcentaje' => 'required_if_accepted:tiene_discapacidad|integer',

            'tiene_familiar_dependiente_discapacitado' => 'boolean',
            'nombre_familiar_dependiente_discapacitado' => 'nullable|required_if_accepted:salud.tiene_familiar_dependiente_discapacitado|string',
            'parentesco_familiar_discapacitado' => 'nullable|required_if_accepted:salud.tiene_familiar_dependiente_discapacitado|string',
            'discapacidades_familiar_dependiente' => 'nullable|required_if_accepted:salud.tiene_familiar_dependiente_discapacitado|array',
            'discapacidades_familiar_dependiente.*.tipo_discapacidad' => 'nullable|required_if_accepted:salud.tiene_familiar_dependiente_discapacitado|string',
            'discapacidades_familiar_dependiente.*.porcentaje' => 'nullable|required_if_accepted:salud.tiene_familiar_dependiente_discapacitado|string',

            'tiene_enfermedad_cronica' => 'boolean',
            'enfermedad_cronica' => 'nullable|required_if_accepted:salud.tiene_enfermedad_cronica|string',
            'alergias' => 'nullable|string',
            'lugar_atencion' => 'required|string',
        ];
    }
}
