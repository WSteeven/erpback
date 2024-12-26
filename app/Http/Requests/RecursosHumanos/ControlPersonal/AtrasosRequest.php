<?php

namespace App\Http\Requests\RecursosHumanos\ControlPersonal;

use Illuminate\Foundation\Http\FormRequest;

class AtrasosRequest extends FormRequest
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
            'atrasos.*.empleado_id' => ['required', 'integer'], // ID del empleado
            'atrasos.*.asistencia_id' => ['required', 'integer'], // ID de la asistencia
            'atrasos.*.fecha_atraso' => ['required', 'date_format:Y-m-d'], // Fecha del atraso
            'atrasos.*.minutos_atraso' => ['required', 'integer', 'min:0'], // Minutos de atraso
            'atrasos.*.segundos_atraso' => ['required', 'integer', 'min:0'], // Segundos de atraso
            'atrasos.*.requiere_justificacion' => ['required', 'boolean'], // Si requiere justificación
            'atrasos.*.justificacion_atraso' => ['nullable', 'string', 'max:500'], // Justificación del atraso (opcional)
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
            'atrasos.*.empleado_id.required' => 'El ID del empleado es obligatorio.',
            'atrasos.*.asistencia_id.required' => 'El ID de la asistencia es obligatorio.',
            'atrasos.*.fecha_atraso.required' => 'La fecha del atraso es obligatoria.',
            'atrasos.*.fecha_atraso.date_format' => 'La fecha del atraso debe tener el formato YYYY-MM-DD.',
            'atrasos.*.minutos_atraso.required' => 'Los minutos de atraso son obligatorios.',
            'atrasos.*.minutos_atraso.integer' => 'Los minutos de atraso deben ser un número entero.',
            'atrasos.*.segundos_atraso.required' => 'Los segundos de atraso son obligatorios.',
            'atrasos.*.segundos_atraso.integer' => 'Los segundos de atraso deben ser un número entero.',
            'atrasos.*.requiere_justificacion.required' => 'Debe indicar si se requiere justificación.',
            'atrasos.*.requiere_justificacion.boolean' => 'El campo de justificación debe ser verdadero o falso.',
            'atrasos.*.justificacion_atraso.string' => 'La justificación debe ser un texto.',
            'atrasos.*.justificacion_atraso.max' => 'La justificación no puede exceder los 500 caracteres.',
        ];
    }
}
