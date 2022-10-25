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
            'detalle' => 'nullable|string',
            'grupo' => 'nullable|numeric|integer',
            'tipo_trabajo' => 'nullable|string',
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
            'es_dependiente' => 'nullable|boolean',
            'subtarea_dependiente' => 'nullable|string',
            'es_ventana' => 'nullable|boolean',
            'hora_inicio_ventana' => 'nullable|string',
            'hora_fin_ventana' => 'nullable|string',
            'descripcion_completa' => 'nullable|string',
            'tecnicos_grupo_principal' => 'nullable|array',
            'tecnicos_otros_grupos' => 'nullable|array',
            'estado' => 'nullable|string',
        ];
    }
}
