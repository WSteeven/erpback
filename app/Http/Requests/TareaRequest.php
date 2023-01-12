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
            // 'codigo_tarea' => 'nullable|string',
            'codigo_tarea_cliente' => 'nullable|string',
            'cliente_final' => 'nullable|numeric|integer',
            'fecha_solicitud' => 'nullable|string',
            'coordinador' => 'nullable|numeric|integer',
            'supervisor' => 'nullable|string',
            'detalle' => 'required|string',
            'cliente' => 'nullable|numeric|integer',
            'destino' => 'required|string',
            'proyecto' => 'nullable|numeric|integer',
            'destino' => 'required|string',
        ];
    }
}
