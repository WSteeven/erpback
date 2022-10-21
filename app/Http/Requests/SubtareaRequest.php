<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubtareaRequest extends FormRequest
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
            'codigo_subtarea' => 'nullable|string',
            'detalle' => 'required|string',
            'grupo_id' => 'required|numeric|integer',
            'tipo_trabajo_id' => 'required|numeric|integer',
            /*'actividad_realizada' => 'nullable|string',
            'novedades' => 'nullable|string',
            'ing_soporte' => 'nullable|string',
            'ing_instalacion' => 'nullable|string',
            'tipo_instalacion' => 'nullable|string',
            'id_servicio' => 'nullable|string',
            'ticket_phoenix' => 'nullable|string',
            'tarea_id' => 'required|numeric|integer',*/
        ];
    }
}
