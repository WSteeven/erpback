<?php

namespace App\Http\Requests\RecursosHumanos\SeleccionContratacion;

use Illuminate\Foundation\Http\FormRequest;

class ExamenRequest extends FormRequest
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
            'canton_id' => 'nullable|sometimes|exists:cantones,id',
            'direccion' => 'nullable|sometimes|string',
            'laboratorio' => 'nullable|sometimes|string',
            'indicaciones' => 'nullable|sometimes|string',
            'se_realizo_examen' => 'boolean',
            'es_apto' => 'boolean',
            'observacion' => 'nullable|sometimes|string',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'canton_id' => $this->canton
        ]);
    }
}
