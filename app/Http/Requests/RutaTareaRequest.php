<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RutaTareaRequest extends FormRequest
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
            'cliente' => 'required|numeric|integer',
            'ruta' => 'required|string',
            'activo' => 'nullable|boolean'
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['cliente'] = 'nullable|numeric|integer';
            $rules['ruta'] = 'nullable|string';
        }

        return $rules;
    }
}
