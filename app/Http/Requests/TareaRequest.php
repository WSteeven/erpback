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
            'codigo_tarea_cliente' => 'nullable|string',
            // 'coordinador' => 'nullable|numeric|integer',
            'cliente_final' => 'nullable|numeric|integer',
            'cliente' => 'required|numeric|integer',
            'fecha_solicitud' => 'nullable|string',
            'supervisor' => 'nullable|string',
            'hora_solicitud' => 'nullable|string',
            'detalle' => 'required|string',
            'supervisor' => 'nullable|numeric|integer',
            'es_proyecto' => 'boolean',
            'codigo_proyecto' => 'nullable|string',
            //
            'celular' => 'nullable|string',
            'parroquia' => 'nullable|string',
            'direccion' => 'nullable|string',
            'referencias' => 'nullable|string',
            'coordenadas' => 'nullable|string',
            'provincia' => 'nullable|numeric|integer',
            'canton' => 'nullable|numeric|integer',
        ];
    }
}
