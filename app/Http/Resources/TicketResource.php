<?php

namespace App\Http\Resources;

use App\Models\Empleado;
use App\Models\Ticket;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $pendiente_calificar_solicitante = $this->verificarPendienteCalificar('SOLICITANTE');
        $pendiente_calificar_responsable = $this->verificarPendienteCalificar('RESPONSABLE');

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
            'solicitante' => $this->solicitante?Empleado::extraerNombresApellidos($this->solicitante):null,
            'solicitante_id' => $this->solicitante_id,
            'responsable' => $this->responsable ? Empleado::extraerNombresApellidos($this->responsable) : null,
            'responsable_id' => $this->responsable_id,
            'departamento_responsable' => $this->departamentoResponsable?->nombre,
            'departamento_solicitante' => $this->solicitante?->departamento?->nombre,
            'tipo_ticket' => $this->tipoTicket?->nombre,
            'categoria_tipo_ticket' => $this->tipoTicket?->categoriaTipoTicket->nombre,
            'fecha_hora_solicitud' => Carbon::parse($this->created_at)->format('d-m-Y H:i:s'),
            'motivo_ticket_no_solucionado' => $this->motivo_ticket_no_solucionado,
            'ticket_interno' => $this->ticket_interno,
            'ticket_para_mi' => $this->ticket_para_mi,
            'puede_ejecutar' => !$this->responsable?->tickets()->where('estado', Ticket::EJECUTANDO)->count(),
            'calificaciones' => $this->calificacionesTickets,
            'pendiente_calificar_solicitante' => $pendiente_calificar_solicitante,
            'pendiente_calificar_responsable' => $pendiente_calificar_responsable,
            'calificado_solicitante' => $this->verificarSiCalificado('SOLICITANTE', $this->solicitante_id),
            'calificado_responsable' => $this->verificarSiCalificado('RESPONSABLE', $this->responsable_id),
            'motivo_cancelado_ticket' => $this->motivoCanceladoTicket?->motivo,
            // 'motivo_rechazado_ticket' => !empty($this->ticketsRechazados) ? end($this->ticketsRechazados->toArray()) : null,
            'motivo_rechazado_ticket' => $this->ticketsRechazados->isNotEmpty() ? $this->ticketsRechazados->last()->motivo : null,
            'tiempo_hasta_finalizar' => $this->calcularTiempoEfectivoTotal(),
            'tiempo_hasta_finalizar_horas' => $this->calcularTiempoEfectivoTotalHoras(),
            // 'tiempo_hasta_finalizar_segundos' => $this->calcularTiempoEfectivoTotalSegundos(),
            'tiempo_hasta_finalizar_departamento' => $this->calcularTiempoEfectivoDepartamento(),
            'tiempo_ocupado_pausas' => $this->calcularTiempoPausado(),
            'destinatarios' => [[
                'departamento_id' => $this->departamento_responsable_id,
                'categoria_id' => $this->tipoTicket?->categoria_tipo_ticket_id,
                'tipo_ticket_id' => $this->tipo_ticket_id,
            ]],
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

    public function verificarSiCalificado(string $solicitante_o_responsable, $idEmpleado)
    {
        return $this->calificacionesTickets()->where('calificador_id', $idEmpleado)->where('solicitante_o_responsable', $solicitante_o_responsable)->exists();
    }

    private function calcularTiempoEfectivoTotal1()
    {
        if (in_array($this->estado, [Ticket::FINALIZADO_SOLUCIONADO, Ticket::FINALIZADO_SIN_SOLUCION])) {
            $tiempos = $this->audits()->get(['auditable_id', 'user_id', 'new_values', 'created_at']);
            $primerEjecucion = $tiempos->first(fn ($tiempo) => isset($tiempo->new_values['estado']) ? $tiempo->new_values['estado'] === Ticket::EJECUTANDO : false);
            $finalizacion = $tiempos->first(fn ($tiempo) => isset($tiempo->new_values['estado']) ? ($tiempo->new_values['estado'] === Ticket::FINALIZADO_SOLUCIONADO || $tiempo->new_values['estado'] === Ticket::FINALIZADO_SIN_SOLUCION) : false);
            return $finalizacion ? CarbonInterval::seconds(Carbon::parse($finalizacion->new_values['fecha_hora_finalizado'])->diffInSeconds(Carbon::parse($primerEjecucion->new_values['fecha_hora_ejecucion'])))->cascade()->forHumans() : null;
        } else {
            return null;
        }
    }

    private function calcularTiempoEfectivoTotal()
    {
        if (in_array($this->estado, [Ticket::FINALIZADO_SOLUCIONADO, Ticket::FINALIZADO_SIN_SOLUCION])) {
            $tiempos = $this->audits()->get(['auditable_id', 'user_id', 'new_values', 'created_at']);

            // Filtrar los tiempos entre la primera ejecución y la finalización del ticket
            $tiemposFiltrados = $tiempos->filter(function ($tiempo) {
                return isset($tiempo->new_values['estado']) &&
                    ($tiempo->new_values['estado'] === Ticket::EJECUTANDO ||
                        $tiempo->new_values['estado'] === Ticket::FINALIZADO_SOLUCIONADO ||
                        $tiempo->new_values['estado'] === Ticket::FINALIZADO_SIN_SOLUCION);
            });

            // Obtener la fecha y hora de la primera ejecución y finalización
            $primerEjecucion = $tiemposFiltrados->first(fn ($tiempo) => $tiempo->new_values['estado'] === Ticket::EJECUTANDO);
            $finalizacion = $tiemposFiltrados->first(fn ($tiempo) => in_array($tiempo->new_values['estado'], [Ticket::FINALIZADO_SOLUCIONADO, Ticket::FINALIZADO_SIN_SOLUCION]));

            // $mensaje = 'Fuera del if';
            // Log::channel('testing')->info('Log', compact('mensaje'));

            $segundosPausas = $this->obtenerSumaPausasSegundos();

            $tiempoOcupado = CarbonInterval::seconds(Carbon::parse($finalizacion?->new_values['fecha_hora_finalizado'])->diffInSeconds(Carbon::parse($primerEjecucion?->new_values['fecha_hora_ejecucion'])));
            $total = $tiempoOcupado->subSeconds($segundosPausas);
            return $finalizacion ? $total->cascade()->forHumans() : null;
            // Calcular el tiempo total ocupado
            /* if ($primerEjecucion && $finalizacion) {
                $mensaje='Dentro del if';
                Log::channel('testing')->info('Log', compact('mensaje'));
                $horaInicio = Carbon::parse($primerEjecucion->new_values['fecha_hora_ejecucion']);
                $horaFin = Carbon::parse($finalizacion->new_values['fecha_hora_finalizado']);

                $diferenciaTotal = 0;

                // Iterar por cada día entre la primera ejecución y la finalización
                while ($horaInicio->lessThanOrEqualTo($horaFin)) {
                    // Descontar el tiempo de almuerzo (12:30 - 14:30)
                    $horaInicio->addHours(2)->addMinutes(30);

                    // Descontar las horas fuera del rango laboral (08:00 - 18:00)
                    $horaInicio = max($horaInicio, Carbon::parse('08:00:00'));
                    Log::channel('testing')->info('Log', compact('horaInicio'));
                    $horaFinActual = min($horaFin, Carbon::parse('18:00:00'));
                    Log::channel('testing')->info('Log', compact('horaFinActual'));

                    // Calcular la diferencia en segundos y sumar al total
                    $diferenciaSegundos = $horaFinActual->diffInSeconds($horaInicio);
                    $diferenciaTotal += $diferenciaSegundos;

                    // Avanzar al siguiente día
                    $horaInicio->addDay();
                }

                // Formatear la diferencia total en horas y minutos
                return CarbonInterval::seconds($diferenciaTotal)->cascade()->forHumans();
            } else {
                return null;
            } */
        } else {
            return null;
        }
    }



    private function calcularTiempoEfectivoTotalHoras()
    {
        if (in_array($this->estado, [Ticket::FINALIZADO_SOLUCIONADO, Ticket::FINALIZADO_SIN_SOLUCION])) {
            $tiempos = $this->audits()->get(['auditable_id', 'user_id', 'new_values', 'created_at']);
            $primerEjecucion = $tiempos->first(fn ($tiempo) => isset($tiempo->new_values['estado']) ? $tiempo->new_values['estado'] === Ticket::EJECUTANDO : false);
            $finalizacion = $tiempos->first(fn ($tiempo) => isset($tiempo->new_values['estado']) ? ($tiempo->new_values['estado'] === Ticket::FINALIZADO_SOLUCIONADO || $tiempo->new_values['estado'] === Ticket::FINALIZADO_SIN_SOLUCION) : false);
            $segundosPausas = $this->obtenerSumaPausasSegundos()->total('seconds');
            $tiempoOcupado = Carbon::parse($finalizacion?->new_values['fecha_hora_finalizado'])->subSeconds($segundosPausas)->diffInHours(Carbon::parse($primerEjecucion?->new_values['fecha_hora_ejecucion']));
            // $total = $tiempoOcupado->subSeconds($segundosPausas);
            return $finalizacion ? $tiempoOcupado : null;
        } else {
            return null;
        }
    }

    private function calcularTiempoEfectivoTotalSegundos()
    {
        if (in_array($this->estado, [Ticket::FINALIZADO_SOLUCIONADO, Ticket::FINALIZADO_SIN_SOLUCION])) {
            $tiempos = $this->audits()->get(['auditable_id', 'user_id', 'new_values', 'created_at']);
            $primerEjecucion = $tiempos->first(fn ($tiempo) => isset($tiempo->new_values['estado']) ? $tiempo->new_values['estado'] === Ticket::EJECUTANDO : false);
            $finalizacion = $tiempos->first(fn ($tiempo) => isset($tiempo->new_values['estado']) ? ($tiempo->new_values['estado'] === Ticket::FINALIZADO_SOLUCIONADO || $tiempo->new_values['estado'] === Ticket::FINALIZADO_SIN_SOLUCION) : false);
            // return $finalizacion ? CarbonInterval::seconds(Carbon::parse($finalizacion->new_values['fecha_hora_finalizado'])->diffInSeconds(Carbon::parse($primerEjecucion->new_values['fecha_hora_ejecucion']))) : null;
            return $finalizacion ? Carbon::parse($finalizacion->new_values['fecha_hora_finalizado'])->diffInSeconds(Carbon::parse($primerEjecucion->new_values['fecha_hora_ejecucion'])) : null;
        } else {
            return null;
        }
    }

    private function calcularTiempoEfectivoDepartamento()
    {
        // return in_array($this->estado, [Ticket::FINALIZADO_SOLUCIONADO, Ticket::FINALIZADO_SIN_SOLUCION]) ? CarbonInterval::seconds(Carbon::parse($this->fecha_hora_finalizado)->diffInSeconds(Carbon::parse($this->fecha_hora_ejecucion)))->cascade()->forHumans() : null;
        if (in_array($this->estado, [Ticket::FINALIZADO_SOLUCIONADO, Ticket::FINALIZADO_SIN_SOLUCION])) {
            $tiempos = $this->audits()->get(['auditable_id', 'user_id', 'new_values', 'created_at']);
            // Log::channel('testing')->info('Log', compact('tiempos'));
            $primerEjecucion = $tiempos->first(fn ($tiempo) => isset($tiempo->new_values['estado']) ? $tiempo->new_values['estado'] === Ticket::EJECUTANDO : false);
            $finalizacion = $tiempos->first(fn ($tiempo) => isset($tiempo->new_values['estado']) ? ($tiempo->new_values['estado'] === Ticket::FINALIZADO_SOLUCIONADO || $tiempo->new_values['estado'] === Ticket::FINALIZADO_SIN_SOLUCION) : false);
            // Log::channel('testing')->info('Log', compact('primerEjecucion'));
            // Log::channel('testing')->info('Log', compact('finalizacion'));
            return $finalizacion ? CarbonInterval::seconds(Carbon::parse($finalizacion->new_values['fecha_hora_finalizado'])->diffInSeconds(Carbon::parse($primerEjecucion->new_values['fecha_hora_ejecucion'])))->cascade()->forHumans() : null;
        } else {
            return null;
        }
    }

    private function calcularTiempoPausado()
    {
        $segundos = $this->obtenerSumaPausasSegundos();
        return $segundos->total('seconds') > 0 ? $segundos->cascade()->forHumans() : null;
        // return $pausas ? $pausas->cascade()->forHumans() : 0;
    }

    private function obtenerSumaPausasSegundos()
    {
        if ($this->pausasTicket->count() > 0) {
            return CarbonInterval::seconds($this->pausasTicket()->sum(DB::raw('TIMESTAMPDIFF(SECOND, fecha_hora_pausa, fecha_hora_retorno)')));
        } else {
            return CarbonInterval::seconds(0);
        }
    }
}
