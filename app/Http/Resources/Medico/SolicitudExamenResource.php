<?php

namespace App\Http\Resources\Medico;

use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SolicitudExamenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $empleado = $this->registroEmpleadoExamen?->empleado;

        return [
            'id' => $this->id,
            'codigo' => 'SOL.EX-' . $this->id,
            'observacion' => $this->observacion,
            'observacion_autorizador' => $this->observacion_autorizador,
            'registro_empleado_examen_id' => $this->registro_empleado_examen_id,
            'registro_empleado_examen' => $this->registro_empleado_examen_id,
            'estado_examen' => $this->estado_examen,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'cantidad_examenes_solicitados' => $this->examenesSolicitados->count(),
            'examenes_solicitados' => EstadoSolicitudExamenResource::collection($this->examenesSolicitados),
            'empleado' => Empleado::extraerNombresApellidos($empleado),
            'empleado_id' => $this->registroEmpleadoExamen->empleado_id,
            'departamento' => $empleado->departamento->nombre,
            'autorizador' => $this->autorizador_id,
            'solicitante' => $this->solicitante_id,
            'canton' => $this->canton_id,
            'estado_solicitud_examen' => $this->estado_solicitud_examen,
        ];
    }
}
