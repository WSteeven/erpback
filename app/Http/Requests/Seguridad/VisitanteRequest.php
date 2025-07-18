<?php

namespace App\Http\Requests\Seguridad;

use Illuminate\Foundation\Http\FormRequest;

class VisitanteRequest extends FormRequest
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
            'nombre_completo' => 'required|string',
            'identificacion' => 'required|string',
            'celular' => 'nullable|string',
            'motivo_visita' => 'required|string',
            'persona_visitada' => 'required|numeric|integer|exists:empleados,id',
            'placa_vehiculo' => 'nullable|string',
            'fecha_hora_ingreso' => 'required|string',
            'fecha_hora_salida' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'actividad_bitacora_id' => 'required|numeric|integer|exists:seg_actividades_bitacoras,id',
        ];
    }
}
