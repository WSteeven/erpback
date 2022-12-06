<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TareaRequest extends FormRequest
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
            'codigo_tarea' => 'nullable|string',
            'codigo_tarea_cliente' => 'required|string',
            'cliente_final' => 'nullable|numeric|integer',
            'cliente' => 'required|numeric|integer',
            'fecha_solicitud' => 'nullable|string',
            'supervisor' => 'nullable|string',
            'hora_solicitud' => 'nullable|string',
            'detalle' => 'required|string',
            'supervisor' => 'nullable|numeric|integer',
            'es_proyecto' => 'boolean',
            'codigo_proyecto' => 'nullable|numeric|integer',
            'ubicacion_tarea' => 'nullable',
        ];
    }
}
