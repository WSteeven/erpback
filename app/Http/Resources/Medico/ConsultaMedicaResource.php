<?php

namespace App\Http\Resources\Medico;

use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Src\App\Medico\FichasMedicasService;

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

        $fichasMedicasService = new FichasMedicasService();

        return [
            'id' => $this->id,
            'rp' => $receta?->rp,
            'receta' => $receta,
            'prescripcion' => $receta?->prescripcion,
            'evolucion' => $this->evolucion,
            'examen_fisico' => $this->examen_fisico,
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
            'constante_vital' => $fichasMedicasService->mapearConstanteVital($this),
        ];
    }

    private function obtenerPaciente($citaMedica)
    {
        $paciente = $citaMedica?->paciente ?? $this->registroEmpleadoExamen?->empleado;
        return Empleado::extraerNombresApellidos($paciente);
    }
}
