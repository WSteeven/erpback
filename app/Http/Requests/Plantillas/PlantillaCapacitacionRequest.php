<?php

namespace App\Http\Requests\Plantillas;

use Illuminate\Foundation\Http\FormRequest;

class PlantillaCapacitacionRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a hacer esta petición.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación.
     */
    public function rules(): array
    {
        return [
            'tema'           => ['required', 'string', 'max:255'],
            'fecha'          => ['required', 'date'],
            'hora_inicio'    => ['required', 'date_format:H:i'],
            'hora_fin'       => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'modalidad'      => ['required', 'in:Interno,Externo'],
            'capacitador_id' => ['required', 'exists:empleados,id'],

            //  Asistentes: deben existir en empleados, es un array
            'asistentes'     => ['nullable', 'array'],
            'asistentes.*'   => ['exists:empleados,id'],
        ];
    }

    /**
     * Mensajes personalizados (opcional).
     */
    public function messages(): array
    {
        return [
            'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'capacitador_id.exists' => 'El capacitador seleccionado no existe.',
            'asistentes.*.exists' => 'Uno o más asistentes seleccionados no existen.',
        ];
    }
}
