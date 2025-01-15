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
            'nombre' => 'required|string',
            'dia' => 'required|string',
            'hora_entrada' => ['required', 'date_format:H:i'],
            'hora_salida' => ['required', 'date_format:H:i'],
            'tiene_pausa' => 'boolean',
            'activo' => 'boolean',
            'inicio_pausa' => ['nullable', 'required_if_accepted:tiene_pausa', 'date_format:H:i'],
            'fin_pausa' => ['nullable', 'required_if_accepted:tiene_pausa', 'date_format:H:i'],
        ];
    }
}
