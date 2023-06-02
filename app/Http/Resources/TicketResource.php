<?php

namespace App\Http\Resources;

use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();

        $modelo = [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'asunto' => $this->asunto,
            'descripcion' => $this->descripcion,
            'prioridad' => $this->prioridad,
            'fecha_hora_limite' => $this->fecha_hora_limite,
            'estado' => $this->estado,
            'observaciones_solicitante' => $this->observaciones_solicitante,
            'calificacion_solicitante' => $this->calificacion_solicitante,
            'solicitante' => Empleado::extraerNombresApellidos($this->solicitante),
            'responsable' => Empleado::extraerNombresApellidos($this->responsable),
            'departamento_responsable' => $this->departamentoResponsable->nombre,
            'tipo_ticket' => $this->tipoTicket->nombre,
            'fecha_hora_solicitud' => Carbon::parse($this->created_at)->format('d-m-Y H:i:s'),
        ];


        if ($controller_method == 'show') {
            $modelo['solicitante'] = $this->solicitante_id;
            $modelo['responsable'] = $this->responsable_id;
            $modelo['departamento_responsable'] = $this->departamento_responsable_id;
            $modelo['tipo_ticket'] = $this->tipo_ticket_id;
        }

        return $modelo;
    }
}
