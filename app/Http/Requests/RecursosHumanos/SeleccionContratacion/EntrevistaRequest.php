<?php

namespace App\Http\Requests\RecursosHumanos\SeleccionContratacion;

use Illuminate\Foundation\Http\FormRequest;

class EntrevistaRequest extends FormRequest
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
            'postulacion_id' => 'required|exists:rrhh_contratacion_postulaciones,id',
            'fecha_hora' => 'required|string',
            'duracion' => 'required',
            'reagendada' => 'boolean',
            'presencial' => 'boolean',
            'canton_id' => 'nullable|sometimes|exists:cantones,id',
            'direccion' => 'nullable|sometimes|string',
            'nueva_fecha_hora' => 'nullable|sometimes|string',
            'observacion' => 'nullable|sometimes|string',
            'link' => 'nullable|sometimes|url:http,https',
            'asistio' => 'boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'canton_id' => $this->canton
        ]);
    }
}
