<?php

namespace App\Http\Requests\RecursosHumanos\SeleccionContratacion;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed $cargo
 * @property mixed $postulacion
 */
class BancoPostulanteRequest extends FormRequest
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
            'cargo_id' => 'required|numeric|exists:cargos,id',
            'postulacion_id' => 'required',
            'puntuacion' => 'required|string',
            'observacion' => 'sometimes|nullable',
            'descartado' => 'boolean',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'cargo_id' => $this->cargo,
            'postulacion_id' => $this->postulacion
        ]);
    }
}
