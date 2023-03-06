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
            'titulo' => 'required|string',
            'observacion' => 'nullable|string',
            'descripcion_completa' => 'nullable|string',
            'grupo' => 'nullable|numeric|integer',
            'empleado' => 'nullable|numeric|integer',
            'tipo_trabajo' => 'required|numeric|integer',
            'fecha_hora_creacion' => 'nullable|string',
            'fecha_hora_asignacion' => 'nullable|string',
            'fecha_hora_ejecucion' => 'nullable|string',
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
            'fecha_agendado' => 'nullable|string',
            'hora_inicio_agendado' => 'nullable|string',
            'hora_fin_agendado' => 'nullable|string',
            'tecnicos_grupo_principal' => 'nullable|string',
            //'tecnicos_otros_grupos' => 'nullable|string',
            'tarea' => 'required|numeric|integer',
            'grupos_seleccionados' => 'nullable|array',
            'empleados_seleccionados' => 'nullable|array',
            'modo_asignacion_trabajo' => 'required|string'
            // 'estado' => 'nullable|numeric|integer',
        ];
    }

    /*public function messages()
    {
        return [
            'tecnicos_grupo_principal.required'=> 'Debe asignar al menos un técnico del grupo asignado.'
        ];
    }*/
}
