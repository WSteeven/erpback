<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TipoTicketRequest extends FormRequest
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
            'nombre' => 'required|string|unique:tipos_tickets',
            'activo' => 'required|boolean',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $id = $this->route('tipo_ticket')->id;
            $rules['nombre'] = [Rule::unique('tipos_tickets')->ignore($id)];
        }

        return $rules;
    }
}
