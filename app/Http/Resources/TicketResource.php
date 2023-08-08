<?php

namespace App\Http\Resources;

use App\Models\Empleado;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
            'solicitante_id' => $this->solicitante_id,
            'responsable' => $this->responsable ? Empleado::extraerNombresApellidos($this->responsable) : null,
            'responsable_id' => $this->responsable_id,
            'departamento_responsable' => $this->departamentoResponsable?->nombre,
            'tipo_ticket' => $this->tipoTicket->nombre,
            'categoria_tipo_ticket' => $this->tipoTicket->categoriaTipoTicket->nombre,
            'fecha_hora_solicitud' => Carbon::parse($this->created_at)->format('d-m-Y H:i:s'),
            'motivo_ticket_no_solucionado' => $this->motivo_ticket_no_solucionado,
            'ticket_interno' => $this->ticket_interno,
            'ticket_para_mi' => $this->ticket_para_mi,
            'puede_ejecutar' => !$this->responsable?->tickets()->where('estado', Ticket::EJECUTANDO)->count(),
            'calificaciones' => $this->calificacionesTickets,
            'pendiente_calificar_solicitante' => $this->verificarPendienteCalificar('SOLICITANTE'),
            'pendiente_calificar_responsable' => $this->verificarPendienteCalificar('RESPONSABLE'),
            'motivo_cancelado_ticket' => $this->motivoCanceladoTicket?->motivo,
        ];


        if ($controller_method == 'show') {
            $modelo['solicitante'] = $this->solicitante_id;
            $modelo['responsable'] = $this->responsable_id;
            $modelo['departamento_responsable'] = $this->departamento_responsable_id;
            $modelo['tipo_ticket'] = $this->tipo_ticket_id;
            $modelo['categoria_tipo_ticket'] = $this->tipoTicket->categoria_tipo_ticket_id;
        }

        return $modelo;
    }

    public function verificarPendienteCalificar(string $solicitante_o_responsable)
    {
        return !$this->calificacionesTickets()->where('calificador_id', Auth::user()->empleado->id)->where('solicitante_o_responsable', $solicitante_o_responsable)->exists();
    }
}
