<?php

namespace App\Http\Requests\Medico;

use App\Models\Medico\EstadoSolicitudExamen;
use App\Models\Medico\SolicitudExamen;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SolicitudExamenRequest extends FormRequest
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
        $enumEstados = [SolicitudExamen::PENDIENTE, SolicitudExamen::SOLICITADO, SolicitudExamen::APROBADO_POR_COMPRAS, SolicitudExamen::RESULTADOS, SolicitudExamen::DIAGNOSTICO_REALIZADO];

        // 'estado_solicitud_examen' => 'required|string',
        return [
            'registro_empleado_examen_id' =>  'required|exists:med_registros_empleados_examenes,id',
            'observacion' => 'nullable|string',
            'estado_solicitud_examen' => ['nullable', 'string', 'in:' . implode(',', $enumEstados)],
            'examenes_solicitados.*.examen' => 'required|exists:med_examenes,id',
            'examenes_solicitados.*.laboratorio_clinico' => 'required|exists:med_laboratorios_clinicos,id',
            'examenes_solicitados.*.fecha_hora_asistencia' => 'required|string',
        ];
    }

    protected function prepareForValidation()
    {
        Log::channel('testing')->info('Log', ['data', 'passed validation']);
        if ($this->isMethod('post')) {
            Log::channel('testing')->info('Log', ['data', 'dentro de if post']);
            $this->merge([
                'estado_solicitud_examen' => SolicitudExamen::SOLICITADO,
            ]);
        }

        $this->merge([
            'registro_empleado_examen_id' => $this->registro_empleado_examen,
        ]);
    }
}
