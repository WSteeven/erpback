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
            'grupo' => 'required|numeric|integer',
            'tipo_trabajo' => 'required|numeric|integer',
            'fecha_hora_creacion' => 'nullable|string',
            'fecha_hora_asignacion' => 'nullable|string',
            'fecha_hora_inicio' => 'nullable|string',
            'fecha_hora_finalizacion' => 'nullable|string',
            'cantidad_dias' => 'nullable|string',
            'fecha_hora_realizado' => 'nullable|string',
            'fecha_hora_suspendido' => 'nullable|string',
            'causa_suspencion' => 'nullable|string',
            'fecha_hora_cancelacion' => 'nullable|string',
            'causa_cancelacion' => 'nullable|string',
            'es_dependiente' => 'boolean',
            'subtarea_dependiente' => 'nullable|numeric|integer',
            'es_ventana' => 'boolean',
            'hora_inicio_ventana' => 'nullable|string',
            'hora_fin_ventana' => 'nullable|string',
            'descripcion_completa' => 'nullable|string',
            'tecnicos_grupo_principal' => 'required|string',
            'tecnicos_otros_grupos' => 'nullable|string',
            'estado' => 'nullable|string',
            'tarea_id' => 'required|numeric|integer',
        ];
    }
}
