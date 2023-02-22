<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrabajoRequest extends FormRequest
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
            'codigo_trabajo' => 'nullable|string',
            //'codigo_trabajo_cliente' => 'required|string',
            'titulo' => 'required|string',
            'descripcion_completa' => 'nullable|string',
            'observacion' => 'nullable|string',
            'tiene_subtrabajos' => 'required|boolean',
            /*'grupo' => 'nullable|numeric|integer',
            'empleado' => 'nullable|numeric|integer',*/
            // 'cantidad_dias' => 'nullable|string',
            'tipo_trabajo' => 'nullable|numeric|integer',
            'fecha_hora_creacion' => 'nullable|string',
            'fecha_hora_asignacion' => 'nullable|string',
            'fecha_hora_ejecucion' => 'nullable|string',
            'fecha_hora_finalizacion' => 'nullable|string',
            'fecha_hora_realizado' => 'nullable|string',
            'fecha_hora_suspendido' => 'nullable|string',
            'causa_suspencion' => 'nullable|string',
            'fecha_hora_cancelacion' => 'nullable|string',
            'causa_cancelacion' => 'nullable|string',
            'trabajo_dependiente' => 'nullable|numeric|integer',
            'es_dependiente' => 'boolean',
            'es_ventana' => 'boolean',
            'fecha_agendado' => 'nullable|string',
            'hora_inicio_agendado' => 'nullable|string',
            'hora_fin_agendado' => 'nullable|string',
            // 'tecnicos_grupo_principal' => 'nullable|string',
            //'tecnicos_otros_grupos' => 'nullable|string',
            // 'tarea_id' => 'required|numeric|integer',
            'grupos_seleccionados' => 'nullable|array',
            'empleados_seleccionados' => 'nullable|array',
            'modo_asignacion_trabajo' => 'required|string',
            // 'estado' => 'nullable|numeric|integer',
            'cliente_final' => 'nullable|numeric|integer',
            'fecha_solicitud' => 'nullable|string',
            'coordinador' => 'nullable|numeric|integer',
            'fiscalizador' => 'nullable|numeric|integer',
            'cliente' => 'nullable|numeric|integer',
            'para_cliente_proyecto' => 'required|string',
            'proyecto' => 'nullable|numeric|integer',
            'trabajo_padre' => 'nullable|numeric|integer',
            'tarea' => 'required|numeric|integer',
        ];
    }
}
