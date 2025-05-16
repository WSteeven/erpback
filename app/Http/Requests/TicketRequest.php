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
        $rules = [
            'asunto' => 'required|string',
            'descripcion' => 'required|string',
            'prioridad' => 'required|string',
            // 'fecha_hora_limite' => 'nullable|string',
            'fecha_hora_limite' => 'nullable|date|after_or_equal:' . now(),
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
            'cc' => 'nullable|array',
            // Recurrente
            'is_recurring' => 'boolean',
            'recurrence_active' => 'boolean',
            'recurrence_frequency' => 'nullable|in:DAILY,WEEKLY,MONTHLY',
            'recurrence_time' => 'nullable|date_format:H:i:s',
            'recurrence_day_of_week' => 'nullable|integer|min:0|max:6|required_if:recurrence_frequency,weekly',
            'recurrence_day_of_month' => 'nullable|integer|min:1|max:31|required_if:recurrence_frequency,monthly',
        ];

        if ($this->isMethod('patch')) {
            $rules = collect($rules)->only(array_keys($this->all()))->toArray(); // Esta regla está bien para pach, verificado el 14/8/2024
        }

        return $rules;
    }

    /* public function all($keys = null)
    {
        $data = parent::all($keys);

        // Convertir ciertos valores a booleanos
        $data['ticket_interno'] = filter_var($data['ticket_interno'], FILTER_VALIDATE_BOOLEAN);
        $data['ticket_para_mi'] = filter_var($data['ticket_para_mi'], FILTER_VALIDATE_BOOLEAN);

        return $data;
    } */

    public function messages()
    {
        return [
            'destinatarios.*.categoria_id' => 'Debe seleccionar una categoría de ticket',
            'destinatarios.*.tipo_ticket_id' => 'Debe seleccionar un tipo de ticket',
        ];
    }
}
