<?php

namespace App\Http\Requests\ControlPersonal;

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
            'asistencias.*.employeeName' => ['required', 'string'], // Nombre del empleado
            'asistencias.*.startTime' => ['required', 'date'], // Hora de ingreso
            'asistencias.*.endTime' => ['nullable', 'date'], // Hora de salida
            'asistencias.*.lunchOutTime' => ['nullable', 'date'], // Hora de salida almuerzo
            'asistencias.*.lunchInTime' => ['nullable', 'date'], // Hora de entrada almuerzo
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
            'asistencias.*.employeeName.required' => 'El nombre del empleado es obligatorio.',
            'asistencias.*.startTime.required' => 'La hora de ingreso es obligatoria.',
        ];
    }
}
