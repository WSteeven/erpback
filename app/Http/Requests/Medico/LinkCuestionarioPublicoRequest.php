<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LinkCuestionarioPublicoRequest extends FormRequest
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
            'link' => ['required', 'string', Rule::unique('med_links_cuestionarios_publicos')->ignore($this->route('id')),],
            'activo' => 'nullable|boolean',
            'cantidad_miembros' => 'nullable|numerc|integer',
        ];

        // Para PATCH, solo validar los campos que se envían en la solicitud
        if ($this->isMethod('patch')) {
            $rules = collect($rules)->only(array_keys($this->all()))->toArray();
        }

        return $rules;
    }
}
