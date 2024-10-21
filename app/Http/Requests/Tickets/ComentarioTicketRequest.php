<?php

namespace App\Http\Requests\Tickets;

use Illuminate\Foundation\Http\FormRequest;

class ComentarioTicketRequest extends FormRequest
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
            'comentario' => 'required|string',
            'empleado_id' => 'required|numeric|integer|exists:empleados,id',
            'ticket_id' => 'required|numeric|integer|exists:tickets,id',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'empleado_id' => auth()->user()->empleado->id,
            'ticket_id' => $this->ticket,
        ]);
    }
}
