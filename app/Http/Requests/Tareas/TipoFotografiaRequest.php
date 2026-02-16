<?php

namespace App\Http\Requests\Tareas;

use Illuminate\Foundation\Http\FormRequest;

class TipoFotografiaRequest extends FormRequest
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
            'nombre' => 'required|string',
            'tipo_trabajo_id' => 'required|exists:tipos_trabajos,id',
            'activo' => 'boolean'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'tipo_trabajo_id' => $this->tipo_trabajo ?? null
        ]);
    }
}
