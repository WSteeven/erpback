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
            // 'fecha_hora_limite' => 'nullable|string',
            'fecha_hora_limite' => 'required|date|after_or_equal:' . now(),
            'observaciones_solicitante' => 'nullable|string',
            'calificacion_solicitante' => 'nullable|string',
            'destinatarios' => 'nullable|array',
            'destinatarios.*.departamento_id' => 'required|numeric|integer',
            'destinatarios.*.categoria_id' => 'required|numeric|integer',
            'destinatarios.*.tipo_ticket_id' => 'required|numeric|integer',
            /*[
                'required',
                'numeric',
                Rule::unique('categorias_tickets', 'id')->ignore($this->route('id')) // Ajusta según tus necesidades
            ], */
            /* 'responsable' => 'required|array',
            'departamento_responsable' => 'required|array',
            'tipo_ticket' => 'required|numeric|integer', */
            'ticket_interno' => 'boolean',
            'ticket_para_mi' => 'boolean',
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);

        // Convertir ciertos valores a booleanos
        $data['ticket_interno'] = filter_var($data['ticket_interno'], FILTER_VALIDATE_BOOLEAN);
        $data['ticket_para_mi'] = filter_var($data['ticket_para_mi'], FILTER_VALIDATE_BOOLEAN);

        return $data;
    }
}
