<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class CuestionarioPublicoRequest extends FormRequest
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
            // Cuestionarios -- 'cuestionario.*.respuesta' => 'nullable|string',
            'cuestionario.*.id_cuestionario' => 'required|numeric|integer',
            'cuestionario.*.respuesta_texto' => 'nullable|string',
            // Persona
            'persona.primer_nombre' => 'required|string',
            'persona.segundo_nombre' => 'required|string',
            'persona.primer_apellido' => 'required|string',
            'persona.segundo_apellido' => 'required|string',
            'persona.area' => 'nullable|string',
            'persona.nivel_academico' => 'nullable|string',
            'persona.antiguedad' => 'nullable|string',
            'persona.correo' => 'nullable|string',
            'persona.genero' => 'nullable|string',
            'persona.nombre_empresa' => 'nullable|string',
            'persona.ruc' => 'nullable|string',
            'persona.cargo' => 'nullable|string',
            'persona.identificacion' => 'nullable|string',
            'persona.fecha_nacimiento' => 'nullable|string|date_format:Y-m-d',
            'persona.tipo_afiliacion_seguridad_social' => 'nullable|string',
            'persona.nivel_instruccion' => 'nullable|string',
            'persona.numero_hijos' => 'nullable|numeric|integer',
            'persona.autoidentificacion_etnica' => 'nullable|string',
            'persona.porcentaje_discapacidad' => 'nullable|string',
            'persona.es_trabajador_sustituto' => 'boolean',
            'persona.enfermedades_preexistentes' => 'nullable|string',
            'persona.ha_recibido_capacitacion' => 'boolean',
            'persona.tiene_examen_preocupacional' => 'boolean',
            'persona.provincia_id' => 'nullable|numeric|integer|exists:provincias,id',
            'persona.canton_id' => 'nullable|numeric|integer|exists:cantones,id',
            'persona.estado_civil_id' => 'nullable',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'persona' => array_merge($this->persona, [
                'estado_civil_id' => $this->persona['estado_civil'] ?? null,
                'provincia_id' => $this->persona['provincia'] ?? null,
                'canton_id' => $this->persona['canton'] ?? null,
                'enfermedades_preexistentes' => implode(', ', $this->persona['enfermedades_preexistentes'] ?? null),
            ]),
        ]);
    }

    public function messages()
    {
        return [
            'persona.fecha_nacimiento' => 'La fecha debe cumplir el formato YYYY-MM-DD (2000-04-16).'
        ];
    }
}
