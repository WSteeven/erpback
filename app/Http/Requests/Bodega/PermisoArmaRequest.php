<?php

namespace App\Http\Requests\Bodega;

use Illuminate\Foundation\Http\FormRequest;

class PermisoArmaRequest extends FormRequest
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
            'nombre' => 'string|required',
            'fecha_emision' => 'string|required',
            'fecha_caducidad' => 'string|required',
            'imagen_permiso' => 'string|required',
            'imagen_permiso_reverso' => 'string|required',
        ];
    }
}
