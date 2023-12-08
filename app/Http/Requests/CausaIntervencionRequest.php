<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CausaIntervencionRequest extends FormRequest
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
            'nombre' => 'required|string',
            'tipo_trabajo' => 'required|numeric|integer',
            'activo' => 'required|boolean',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['tipo_trabajo'] = 'nullable|numeric|integer';
        }

        return $rules;
    }
}
