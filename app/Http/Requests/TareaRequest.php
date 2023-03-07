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
            'codigo_tarea_cliente' => 'nullable|string',
            'fecha_solicitud' => 'nullable|string',
            'titulo' => 'required|string',
            'observacion' => 'nullable|string',
            'para_cliente_proyecto' => 'required|string',
            'cliente' => 'nullable|numeric|integer',
            'coordinador' => 'nullable|numeric|integer',
            'fiscalizador' => 'nullable|numeric|integer',
            'proyecto' => 'nullable|numeric|integer',
            'cliente_final' => 'nullable|numeric|integer',
            'medio_notificacion' => 'required|string',
            'tiene_subtareas' => 'required|boolean',
            'subtarea' => 'nullable'
        ];
    }
}
