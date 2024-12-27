<?php

namespace App\Http\Requests\SSO;

use Illuminate\Foundation\Http\FormRequest;

class CertificacionRequest extends FormRequest
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
            'descripcion' => 'required|string',
            'activo' => 'boolean',
        ];

        // Para PATCH, solo validar los campos que se envían en la solicitud
        if ($this->isMethod('patch')) {
            $rules = collect($rules)->only(array_keys($this->all()))->toArray(); // Esta regla está bien para pach, verificado el 14/8/2024
        }

        return $rules;
    }
}
