<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoriaTipoTicketRequest extends FormRequest
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
            'departamento' => 'required|numeric|integer',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            /*$id = $this->route('categoria_tipo_ticket')->id;
            $rules['nombre'] = [Rule::unique('categorias_tipos_tickets')->ignore($id)];*/
            $rules['nombre'] = 'nullable';
            $rules['departamento'] = 'nullable';
        }

        return $rules;
    }
}
