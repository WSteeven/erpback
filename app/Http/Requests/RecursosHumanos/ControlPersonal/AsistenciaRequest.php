<?php

namespace App\Http\Requests\RecursosHumanos\ControlPersonal;

use Illuminate\Foundation\Http\FormRequest;

class AsistenciaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Permitir la ejecución siempre
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'asistencias' => ['required', 'array'], // Validar que sea un array
            'asistencias.*.empleado_id' => ['required', 'integer'], // Nombre del empleado
            'asistencias.*.hora_ingreso' => ['required', 'date'], // Hora de ingreso
            'asistencias.*.hora_salida' => ['nullable', 'date'], // Hora de salida
            'asistencias.*.hora_salida_almuerzo' => ['nullable', 'date'], // Hora de salida almuerzo
            'asistencias.*.hora_entrada_almuerzo' => ['nullable', 'date'], // Hora de entrada almuerzo
        ];
    }

    /**
     * Mensajes personalizados de validación.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'asistencias.required' => 'Los datos de asistencia son obligatorios.',
            'asistencias.*.empleado.required' => 'El nombre del empleado es obligatorio.',
            'asistencias.*.hora_ingreso.required' => 'La hora de ingreso es obligatoria.',
        ];
    }
}
