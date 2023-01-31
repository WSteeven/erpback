<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $rules = [
            'nombre' => 'required|string|unique:proyectos',
            'codigo_proyecto' => 'required|string|unique:proyectos',
            'cliente' => 'required|numeric|integer',
            'canton' => 'required|numeric|integer',
            'coordinador' => 'required|numeric|integer',
            'fiscalizador' => 'nullable|numeric|integer',
            'fecha_inicio' => 'required|string',
            'fecha_fin' => 'required|string',
        ];

        if(in_array($this->method(), ['PUT', 'PATCH'])){
            $nombre = $this->route()->parameter('nombre');
            $rules['nombre'] = ['required', 'string', Rule::unique('proyectos')->ignore($this->id)];

            $codigo_proyecto = $this->route()->parameter('codigo_proyecto');
            $rules['codigo_proyecto'] = ['required', 'string', Rule::unique('proyectos')->ignore($this->id)];
        }

        return $rules;
    }
}
