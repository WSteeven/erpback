<?php

namespace App\Http\Requests\Tareas;

use Illuminate\Foundation\Http\FormRequest;

class NodoRequest extends FormRequest
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
            'grupos' => 'required|array',
            'grupos.*' => 'required|integer|exists:grupos,id',
            'coordinador_id' => 'required|exists:empleados,id',
            'nombre' => 'required|string',
            'activo' => 'boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'coordinador_id' => $this->coordinador,
        ]);
    }
}
