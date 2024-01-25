<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActividadRealizadaSeguimientoTicketRequest extends FormRequest
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
            'fecha_hora' => 'required|string',
            'actividad_realizada' => 'required|string',
            'observacion' => 'nullable|string',
            'fotografia' => 'nullable|string',
            'ticket' => 'required|numeric|integer',
            'responsable' => 'required|numeric|integer',
        ];
    }
}
