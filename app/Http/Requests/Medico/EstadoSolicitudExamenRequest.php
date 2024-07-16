<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class EstadoSolicitudExamenRequest extends FormRequest
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
            'registro_empleado_examen' =>  'required|exists:med_registros_empleados_examenes,id',
            // 'examen_id' => 'required|exists:med_tipos_examenes,id',
            // 'estado_examen_id' => 'required|exists:med_estados_examenes,id',
            // 'laboratorio_clinico_id' => 'required|exists:med_laboratorios_clinicos,id',
            'observacion' => 'nullable|string',
            'examenes_solicitados.*.examen' => 'required|exists:med_examenes,id',
            'examenes_solicitados.*.estado_examen' => 'nullable|exists:med_estados_examenes,id',
            'examenes_solicitados.*.laboratorio_clinico' => 'required|exists:med_laboratorios_clinicos,id',
            'examenes_solicitados.*.fecha_hora_asistencia' => 'required|string',
        ];
    }

    /* protected function prepareForValidation()
    {
        $this->merge([
            'registro_id' => $this->registro_empleado_examen,
            'tipo_examen_id' => $this->tipo_examen,
            'estado_examen_id' => $this->estado_examen
        ]);
    } */
}
