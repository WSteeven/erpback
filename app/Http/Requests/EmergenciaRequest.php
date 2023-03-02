<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmergenciaRequest extends FormRequest
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
            'regional' => 'required|string',
            'atencion' => 'required|string',
            'tipo_intervencion' => 'required|string',
            'causa_intervencion' => 'required|string',
            'fecha_reporte_problema' => 'required|string',
            'hora_reporte_problema' => 'required|string',
            'fecha_arribo' => 'required|string',
            'hora_arribo' => 'required|string',
            'fecha_fin_reparacion' => 'required|string',
            'hora_fin_reparacion' => 'required|string',
            'fecha_retiro_personal' => 'required|string',
            'hora_retiro_personal' => 'required|string',
            'tiempo_espera_adicional' => 'nullable|string',
            'estacion_referencia_afectacion' => 'nullable|string',
            'distancia_afectacion' => 'nullable|string',
            'trabajo_realizado' => 'required|array',
            'observaciones' => 'required|array',
            'materiales_ocupados' => 'required|array',
            // 'subtarea_id' => 'required|string',
        ];
    }
}
