<?php

namespace App\Http\Requests\RecursosHumanos\ControlPersonal;

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
            'hora_entrada' => ['required', 'date_format:H:i'], // Hora de entrada en formato HH:MM, obligatoria
            'hora_salida' => ['required', 'date_format:H:i'], // Hora de salida en formato HH:MM, obligatoria
        ];
    }
}
