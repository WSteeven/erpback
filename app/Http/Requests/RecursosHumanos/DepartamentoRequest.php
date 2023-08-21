<?php

namespace App\Http\Requests\RecursosHumanos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartamentoRequest extends FormRequest
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
        $rules = [
            'nombre' => 'required|string|unique:departamentos',
            'activo' => 'required|boolean',
            'responsable' => 'required|numeric|integer',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $id = $this->route('departamento')->id;
            $rules['nombre'] = [Rule::unique('departamentos')->ignore($id)];
        }

        return $rules;
    }
}
