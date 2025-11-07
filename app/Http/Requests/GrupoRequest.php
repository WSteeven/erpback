<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GrupoRequest extends FormRequest
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
            'nombre_alternativo' => 'sometimes|nullable|string',
            'region' => 'nullable|string',
            'activo' => 'required|boolean',
            'coordinador_id' => 'required|numeric|integer',
            'vehiculo_id' => 'sometimes|nullable|integer|exists:vehiculos,id',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'coordinador_id' => $this->coordinador,
            'vehiculo_id' => $this->vehiculo,
        ]);
    }
}
