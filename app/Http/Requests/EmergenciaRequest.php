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
            'regional',
            'atencion',
            'tipo_intervencion',
            'causa_intervencion',
            'fecha_reporte_problema',
            'hora_reporte_problema',
            'fecha_arribo',
            'hora_arribo',
            'fecha_fin_reparacion',
            'hora_fin_reparacion',
            'fecha_retiro_personal',
            'hora_retiro_personal',
            'tiempo_espera_adicional',
            'estacion_referencia_afectacion',
            'distancia_afectacion',
            'trabajo_realizado',
            'observaciones',
            'materiales_ocupados',
            'subtarea_id',
        ];
    }
}
