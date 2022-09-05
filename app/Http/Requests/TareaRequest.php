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
            'codigo_tarea_jp' => 'nullable|string',
            'codigo_tarea_cliente' => 'nullable|string',
            'fecha_inicio' => 'nullable|date',
            'fecha_finalizacion' => 'nullable|date',
            'solicitante' => 'required|string',
            'cliente' => 'required|numeric|integer',
            'correo_solicitante' => 'nullable|string',
            'detalle' => 'required|string',
            'es_proyecto' => 'boolean',
            'codigo_proyecto' => 'nullable|string',
        ];
    }
}
