<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProyectoRequest extends FormRequest
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
            'nombre' => 'required|string|unique:proyectos',
            'codigo_proyecto' => 'required|string',
            'cliente' => 'required|numeric|integer',
            'canton' => 'required|numeric|integer',
            'coordinador' => 'required|numeric|integer',
            'fecha_inicio' => 'required|string',
            'fecha_fin' => 'required|string',
            'costo' => 'required|numeric|',
        ];
    }
}
