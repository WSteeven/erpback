<?php

namespace App\Http\Resources\Medico;

use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class ConsultaMedicaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $receta = $this->receta;
        $citaMedica = $this->citaMedica;

        return [
            'id' => $this->id,
            'rp' => $receta?->rp,
            'receta' => $receta,
            'prescripcion' => $receta?->prescripcion,
            'observacion' => $this->observacion,
            'cita_medica_id' => $this->cita_medica_id,
            'tipo_cita_medica' => $this->citaMedica?->tipo_cita_medica,
            'registro_empleado_examen_id' => $this->registro_empleado_examen_id,
            'diagnosticos' => DiagnosticoCitaResource::collection($this->diagnosticosCitaMedica),
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'dado_alta' => $this->dado_alta,
            'paciente' => $this->obtenerPaciente($citaMedica),
            'sintomas' => $citaMedica?->sintomas,
            'fecha_hora_solicitud' => Carbon::parse($citaMedica?->created_at)->format('Y-m-d H:i:s'),
            'dias_descanso' => $this->dias_descanso,
        ];
    }

    private function obtenerPaciente($citaMedica)
    {
        Log::channel('testing')->info('Log', ['consulta antes', 'aaaaaaa']);
        Log::channel('testing')->info('Log', ['consulta', $citaMedica]);
        $paciente = $citaMedica?->paciente ?? $this->registroEmpleadoExamen?->empleado;
        Log::channel('testing')->info('Log', ['paciente', $paciente]);
        return Empleado::extraerNombresApellidos($paciente);
        //Empleado::extraerNombresApellidos($citaMedica?->paciente), // $citaMedica?->paciente, // Empleado::extraerNombresApellidos($citaMedica?->paciente),
    }

    // jaime 255 hebillas 
    //cinta 7122 jaime
}
