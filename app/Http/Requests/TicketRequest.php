<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
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
            'asunto' => 'required|string',
            'descripcion' => 'required|string',
            'prioridad' => 'required|string',
            'fecha_hora_limite' => 'nullable|string',
            'observaciones_solicitante' => 'nullable|string',
            'calificacion_solicitante' => 'nullable|string',
            'responsable' => 'required|numeric|integer',
            'departamento_responsable' => 'required|numeric|integer',
            'tipo_ticket' => 'required|numeric|integer',
        ];
    }
}
