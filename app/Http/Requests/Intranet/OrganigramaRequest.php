<?php

namespace App\Http\Requests\Intranet;

use Illuminate\Foundation\Http\FormRequest;

class OrganigramaRequest extends FormRequest
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
            'empleado_id' => 'required|exists:empleados,id', // Validar que el empleado exista en la tabla empleados
            'cargo' => 'required|string|max:191', // Validar que el cargo sea un string requerido con máximo de 191 caracteres
            'jefe_id' => 'nullable|exists:intra_organigrama,id', // Validar que el jefe sea opcional y exista en la misma tabla intra_organigrama
            'departamento' => 'required|string|max:191', // Validar que el departamento sea requerido y un string con máximo de 191 caracteres
            'nivel' => 'required|integer|min:1|max:255', // Validar que el nivel sea un entero entre 1 y 255 (TinyInt)
            'tipo' => 'required|in:interno,externo', // Validar que el tipo sea 'interno' o 'externo'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Permitir que el empleado pueda seleccionarse a sí mismo como jefe inmediato
        $this->merge([
            'jefe_id' => $this->jefe_id ?: null, // Asignar null si jefe_id está vacío
        ]);

        // Si el nivel no es proporcionado o no es un número válido, establecerlo en 1
        if (!is_numeric($this->nivel) || $this->nivel < 1) {
            $this->merge([
                'nivel' => 1,
            ]);
        }

        // Normalizar el tipo a minúsculas para evitar problemas de validación
        if ($this->tipo) {
            $this->merge([
                'tipo' => strtolower($this->tipo),
            ]);
        }
    }
}
