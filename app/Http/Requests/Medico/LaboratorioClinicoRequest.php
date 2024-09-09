<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class LaboratorioClinicoRequest extends FormRequest
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
            'nombre' => 'required|string',
            'direccion' => 'required|string',
            'celular' => 'required|string',
            'correo' => 'nullable|string',
            'coordenadas' => 'nullable|string',
            'activo' => 'nullable|boolean',
            'canton_id' => 'required|exists:cantones,id',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'canton_id' => $this->canton,
        ]);
    }
}
