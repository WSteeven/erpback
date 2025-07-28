<?php

namespace App\Http\Requests\Medico;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class CitaMedicaRequest extends FormRequest
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
            'sintomas' => 'required|string',
            'observacion' => 'nullable|string',
            'fecha_hora_cita' => 'nullable|string',//|date_format:Y-m-d H:i:s',
            'fecha_hora_accidente' => 'nullable|string',//|date_format:Y-m-d H:i:s',
            'estado_cita_medica' => 'required|string',//exists:med_estados_citas_medicas,id',
            'tipo_cita_medica' => 'required|string',
            'paciente_id' => 'required|exists:empleados,id',
            'motivo_rechazo' => 'nullable|string',
            'motivo_cancelacion' => 'nullable|string',
            'tipo_cambio_cargo' => 'nullable|string',
            'accidente_id' => 'nullable|numeric|integer|exists:sso_accidentes,id',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            // 'fecha_hora_cita' => Carbon::parse($this->fecha_hora_cita)->format('Y-m-d H:i:s'),
            // 'estado_cita_medica_id' => $this->estado_cita_medica,
            'paciente_id' => $this->paciente,
            'accidente_id' => $this->accidente,
            'fecha_hora_cita' => $this->fecha_hora_cita ?? ($this->fecha_cita_medica && $this->hora_cita_medica ? Carbon::parse($this->fecha_cita_medica . ' ' . $this->hora_cita_medica) : null)
        ]);
    }
}
