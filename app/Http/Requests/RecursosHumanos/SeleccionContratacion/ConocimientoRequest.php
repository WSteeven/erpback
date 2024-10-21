<?php

namespace App\Http\Requests\RecursosHumanos\SeleccionContratacion;

use Illuminate\Foundation\Http\FormRequest;

class ConocimientoRequest extends FormRequest
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
            'nombre' => 'required|string|unique:rrhh_contratacion_conocimientos,nombre,NULL,id,cargo_id,' . $this->cargo_id,
            'cargo_id' => 'required|numeric|exists:cargos,id|unique:cargos,nombre',
            'activo' => 'boolean'
        ];
    }

    public function messages()
    {
        return [
            'nombre.unique' => 'El conocimiento ya existe para el cargo seleccionado',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->cargo) $this->merge(['cargo_id' => $this->cargo]);
    }
}
