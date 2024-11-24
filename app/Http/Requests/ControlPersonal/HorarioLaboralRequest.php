<?php

namespace App\Http\Requests\ControlPersonal;

use Illuminate\Foundation\Http\FormRequest;

class HorarioLaboralRequest extends FormRequest
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
            'horarios' => ['required', 'array'], // Validar que sea un array
            'horarios.*.horaEntrada' => ['required', 'date_format:H:i'], // Hora de entrada en formato HH:MM, obligatoria
            'horarios.*.horaSalida' => ['required', 'date_format:H:i'], // Hora de salida en formato HH:MM, obligatoria
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
            'horarios.required' => 'Los datos de los horarios son obligatorios.',
            'horarios.*.horaEntrada.required' => 'La hora de entrada es obligatoria.',
            'horarios.*.horaEntrada.date_format' => 'La hora de entrada debe estar en el formato HH:MM.',
            'horarios.*.horaSalida.required' => 'La hora de salida es obligatoria.',
            'horarios.*.horaSalida.date_format' => 'La hora de salida debe estar en el formato HH:MM.',
        ];
    }
}
