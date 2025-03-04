<?php

namespace App\Http\Requests\Seguridad;

use Illuminate\Foundation\Http\FormRequest;

class ActividadBitacoraRequest extends FormRequest
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
        $rules = [
            'fecha_hora_inicio' => 'required|string',
            'fecha_hora_fin' => 'nullable|string',
            'notificacion_inmediata' => 'boolean',
            'actividad' => 'required|string',
            'fotografia_evidencia_1' => 'nullable|string',
            'fotografia_evidencia_2' => 'nullable|string',
            'medio_notificacion' => 'nullable|string',
            'tipo_evento_bitacora_id' => 'required|numeric|integer|exists:seg_tipos_eventos_bitacoras,id',
            'bitacora_id' => 'required|numeric|integer|exists:seg_bitacoras,id',
            'visitante' => 'nullable',
            'visitante.nombre_completo' => 'sometimes|string',
            'visitante.identificacion' => 'sometimes|string',
            'visitante.celular' => 'sometimes|nullable|string|max:10',
            'visitante.motivo_visita' => 'sometimes|string',
            'visitante.persona_visitada' => 'sometimes|numeric|integer|exists:empleados,id',
            'visitante.placa_vehiculo' => 'sometimes|nullable|string|max:8',
            'visitante.observaciones' => 'sometimes|nullable|string',
        ];

        if ($this->isMethod('patch')) {
            $rules = collect($rules)->only(array_keys($this->all()))->toArray(); // Esta regla está bien para pach, verificado el 14/8/2024
        }

        return $rules;
        /* $rules = [
            'fecha_hora_inicio' => 'required|string',
            'fecha_hora_fin' => 'nullable|string',
            'notificacion_inmediata' => 'boolean',
            'actividad' => 'required|string',
            'fotografia_evidencia_1' => 'nullable|string',
            'fotografia_evidencia_2' => 'nullable|string',
            'tipo_evento_id' => 'required|numeric|integer|exists:seg_tipos_eventos_bitacoras,id',
        ];

        // Si "tipo_evento_id" es 1, entonces validar los campos de visitante
        if ($this->input('tipo_evento_id') == 1) {
            $rules = array_merge($rules, [
                'visitante.nombre_completo' => 'required|string',
                'visitante.identificacion' => 'required|string',
                'visitante.celular' => 'required|string|max:10',
                'visitante.motivo_visita' => 'required|string',
                'visitante.persona_visitada' => 'required|numeric|integer|exists:empleados,id',
                'visitante.placa_vehiculo' => 'required|string|max:8',
                'visitante.observaciones' => 'required|string',
            ]);
        }

        return $rules; */
    }

    protected function prepareForValidation()
    {
        if ($this->isMethod('post')) {
            $this->merge([
                'tipo_evento_bitacora_id' => $this['tipo_evento_bitacora'],
                'bitacora_id' => $this['bitacora'],
            ]);
        }
    }
}
