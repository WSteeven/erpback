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
            'solicitante' => $this->solicitante ? Empleado::extraerNombresApellidos($this->solicitante) : null,
            'solicitante_id' => $this->solicitante_id,
            'responsable' => $this->responsable ? Empleado::extraerNombresApellidos($this->responsable) : null,
            'responsable_id' => $this->responsable_id,
            'departamento_responsable' => $this->departamentoResponsable?->nombre,
            'departamento_solicitante' => $this->solicitante?->departamento?->nombre,
            'tipo_ticket' => $this->tipoTicket?->nombre,
            'categoria_tipo_ticket' => $this->tipoTicket?->categoriaTipoTicket->nombre,
            'fecha_hora_solicitud' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'motivo_ticket_no_solucionado' => $this->motivo_ticket_no_solucionado,
            'ticket_interno' => $this->ticket_interno,
            'ticket_para_mi' => $this->ticket_para_mi,
            'puede_ejecutar' => !$this->responsable?->tickets()->where('estado', Ticket::EJECUTANDO)->count(), // && $this->responsable_id === Auth::user()->empleado->id,
            'calificaciones' => $this->calificacionesTickets,
            'pendiente_calificar_solicitante' => $pendiente_calificar_solicitante,
            'pendiente_calificar_responsable' => $pendiente_calificar_responsable,
            'calificado_solicitante' => $this->verificarSiCalificado('SOLICITANTE', $this->solicitante_id),
            'calificado_responsable' => $this->verificarSiCalificado('RESPONSABLE', $this->responsable_id),
            'motivo_cancelado_ticket' => $this->motivoCanceladoTicket?->motivo,
            // 'motivo_rechazado_ticket' => !empty($this->ticketsRechazados) ? end($this->ticketsRechazados->toArray()) : null,
            'motivo_rechazado_ticket' => $this->ticketsRechazados->isNotEmpty() ? $this->ticketsRechazados->last()->motivo : null,
            'primera_fecha_hora_ejecucion' => $this->obtenerPrimeraEjecucion(),
            'fecha_hora_ejecucion' => $this->fecha_hora_ejecucion,
            'fecha_hora_finalizado' => $this->fecha_hora_finalizado,
            'tiempo_hasta_finalizar' => $this->obtenerTiempoHastaFinalizar(), //calcularTiempoEfectivoTotal(),
            'tiempo_hasta_finalizar_h_m_s' => $this->convertirSegundosAFormato($this->calcularTiempoEfectivoTotalRealSinPausas()), //calcularTiempoEfectivoTotalHorasMinutosSegundos(),
            'tiempo_hasta_finalizar_horas' => $this->calcularTiempoEfectivoTotalHoras(),
            // 'tiempo_hasta_finalizar_segundos' => $this->calcularTiempoEfectivoTotalSegundos(),
            'tiempo_hasta_finalizar_departamento' => $this->calcularTiempoEfectivoDepartamento(),
            'tiempo_ocupado_pausas' => $this->calcularTiempoPausado(),
            'created_at' => $this->created_at,
            'destinatarios' => [[
                'departamento_id' => $this->departamento_responsable_id,
                'categoria_id' => $this->tipoTicket?->categoria_tipo_ticket_id,
                'tipo_ticket_id' => $this->tipo_ticket_id,
            ]],
            'cc' => $this->cc ? Empleado::obtenerNombresApellidosEmpleados(json_decode($this->cc)) : null,
        ];

        if ($controller_method == 'show') {
            $modelo['solicitante'] = $this->solicitante_id;
            $modelo['responsable'] = $this->responsable_id;
            $modelo['departamento_responsable'] = $this->departamento_responsable_id;
            $modelo['tipo_ticket'] = $this->tipo_ticket_id;
            $modelo['categoria_tipo_ticket'] = $this->tipoTicket->categoria_tipo_ticket_id;
            $modelo['cc'] = json_decode($this->cc);
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

    private function obtenerPrimeraEjecucion()
    {
        $tiempos = $this->audits()->orderBy('id', 'ASC')->get(['auditable_id', 'user_id', 'new_values', 'created_at']);
        $finalizacion = $tiempos->first(fn($tiempo) => isset($tiempo->new_values['estado']) ? $tiempo->new_values['estado'] === Ticket::EJECUTANDO : false);
        return $finalizacion && isset($finalizacion->new_values['fecha_hora_ejecucion']) ? Carbon::parse($finalizacion->created_at)->format('Y-m-d H:i:s') : null;
    }

    private function obtenerTiempoHastaFinalizar()
    {
        $segundosOcupados = $this->calcularTiempoEfectivoTotalRealSinPausas();
        return $segundosOcupados ? CarbonInterval::seconds($segundosOcupados)->cascade()->forHumans() : null;
    }

    /* private function calcularTiempoEfectivoTotal1()
    {
        if (in_array($this->estado, [Ticket::FINALIZADO_SOLUCIONADO, Ticket::FINALIZADO_SIN_SOLUCION])) {
            $tiempos = $this->audits()->get(['auditable_id', 'user_id', 'new_values', 'created_at']);
            $primerEjecucion = $tiempos->first(fn($tiempo) => isset($tiempo->new_values['estado']) ? $tiempo->new_values['estado'] === Ticket::EJECUTANDO : false);
            $finalizacion = $tiempos->first(fn($tiempo) => isset($tiempo->new_values['estado']) ? ($tiempo->new_values['estado'] === Ticket::FINALIZADO_SOLUCIONADO || $tiempo->new_values['estado'] === Ticket::FINALIZADO_SIN_SOLUCION) : false);
            return $finalizacion ? CarbonInterval::seconds(Carbon::parse($finalizacion->new_values['fecha_hora_finalizado'])->diffInSeconds(Carbon::parse($primerEjecucion->new_values['fecha_hora_ejecucion'])))->cascade()->forHumans() : null;
        } else {
            return null;
        }
    } */

    /* private function calcularTiempoEfectivoTotal()
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
            $primerEjecucion = $tiemposFiltrados->first(fn($tiempo) => $tiempo->new_values['estado'] === Ticket::EJECUTANDO);
            $finalizacion = $tiemposFiltrados->first(fn($tiempo) => in_array($tiempo->new_values['estado'], [Ticket::FINALIZADO_SOLUCIONADO, Ticket::FINALIZADO_SIN_SOLUCION]));

            $segundosPausas = $this->obtenerSumaPausasSegundos();

            $tiempoOcupado = CarbonInterval::seconds(Carbon::parse($finalizacion?->new_values['fecha_hora_finalizado'])->diffInSeconds(Carbon::parse($primerEjecucion?->new_values['fecha_hora_ejecucion'])));
            $total = $tiempoOcupado->subSeconds($segundosPausas);
            return $finalizacion ? $total->cascade()->forHumans() : null;
        } else {
            return null;
        }
    } */

    // aQUI ESTOY verificando la resta de las pausas, las pausas estan bien
    /* private function calcularTiempoEfectivoTotalHorasMinutosSegundos()
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
            $primerEjecucion = $this->obtenerPrimeraEjecucion(); //$tiemposFiltrados->first(fn($tiempo) => $tiempo->new_values['estado'] === Ticket::EJECUTANDO);
            $finalizacion = $tiemposFiltrados->first(fn($tiempo) => in_array($tiempo->new_values['estado'], [Ticket::FINALIZADO_SOLUCIONADO, Ticket::FINALIZADO_SIN_SOLUCION]));

            $segundosPausas = $this->obtenerSumaPausasSegundos();
            // $segundosPausas = $segundosPausas->total('seconds');
            Log::channel('testing')->info('Log', compact('segundosPausas'));

            // $tiempoOcupado = CarbonInterval::seconds(Carbon::parse($finalizacion?->new_values['fecha_hora_finalizado'])->diffInSeconds(Carbon::parse($primerEjecucion?->new_values['fecha_hora_ejecucion'])));
            $tiempoOcupado = CarbonInterval::seconds(Carbon::parse($finalizacion?->new_values['fecha_hora_finalizado'])->diffInSeconds(Carbon::parse($primerEjecucion)));
            Log::channel('testing')->info('Log', ['auditt' => Carbon::parse($finalizacion?->created_at)->format('Y-m-d H:i:s')]);
            Log::channel('testing')->info('Log', compact('tiempoOcupado'));
            $total = $tiempoOcupado->subSeconds($segundosPausas);
            Log::channel('testing')->info('Log', compact('total'));
            return $finalizacion ? $this->convertirSegundosAFormato($total->seconds) : null;
            // return $finalizacion ? $this->convertirSegundosAFormato($total) : null;
        } else {
            return null;
        }
    } */

    function convertirSegundosAFormato(int|null $segundos): string|null
    {
        if (!$segundos) return null;

        // Calcular horas totales, minutos y segundos
        $horas = floor($segundos / 3600); // Obtener el total de horas
        $minutos = floor(($segundos % 3600) / 60); // Obtener los minutos restantes
        $segundosRestantes = $segundos % 60; // Obtener los segundos restantes

        // Formatear en H:i:s
        return sprintf('%02d:%02d:%02d', $horas, $minutos, $segundosRestantes);
    }

    private function calcularTiempoEfectivoTotalHoras()
    {
        if (in_array($this->estado, [Ticket::FINALIZADO_SOLUCIONADO, Ticket::FINALIZADO_SIN_SOLUCION])) {
            $tiempos = $this->audits()->get(['auditable_id', 'user_id', 'new_values', 'created_at']);
            $primerEjecucion = $tiempos->first(fn($tiempo) => isset($tiempo->new_values['estado']) ? $tiempo->new_values['estado'] === Ticket::EJECUTANDO : false);
            $finalizacion = $tiempos->first(fn($tiempo) => isset($tiempo->new_values['estado']) ? ($tiempo->new_values['estado'] === Ticket::FINALIZADO_SOLUCIONADO || $tiempo->new_values['estado'] === Ticket::FINALIZADO_SIN_SOLUCION) : false);
            $segundosPausas = $this->obtenerSumaPausasSegundos()->total('seconds');
            $tiempoOcupado = Carbon::parse($finalizacion?->new_values['fecha_hora_finalizado'])->subSeconds($segundosPausas)->diffInHours(Carbon::parse($primerEjecucion?->new_values['fecha_hora_ejecucion']));
            // $total = $tiempoOcupado->subSeconds($segundosPausas);
            return $finalizacion ? $tiempoOcupado : null;
        } else {
            return null;
        }
    }

    private function calcularTiempoEfectivoTotalRealSinPausas()
    {
        if (in_array($this->estado, [Ticket::FINALIZADO_SOLUCIONADO, Ticket::FINALIZADO_SIN_SOLUCION])) {
            $tiempos = $this->audits()->get(['auditable_id', 'user_id', 'new_values', 'created_at']);
            $primerEjecucion = $tiempos->first(fn($tiempo) => isset($tiempo->new_values['estado']) ? $tiempo->new_values['estado'] === Ticket::EJECUTANDO : false);
            $finalizacion = $tiempos->first(fn($tiempo) => isset($tiempo->new_values['estado']) ? ($tiempo->new_values['estado'] === Ticket::FINALIZADO_SOLUCIONADO || $tiempo->new_values['estado'] === Ticket::FINALIZADO_SIN_SOLUCION) : false);
            $segundosPausas = $this->obtenerSumaPausasSegundos()->total('seconds');
            $tiempoOcupado = Carbon::parse($finalizacion?->new_values['fecha_hora_finalizado'])->subSeconds($segundosPausas)->diffInSeconds(Carbon::parse($primerEjecucion?->new_values['fecha_hora_ejecucion']));
            return $finalizacion ? $tiempoOcupado : null;
        } else {
            return null;
        }
    }

    /* private function calcularTiempoEfectivoTotalSegundos()
    {
        if (in_array($this->estado, [Ticket::FINALIZADO_SOLUCIONADO, Ticket::FINALIZADO_SIN_SOLUCION])) {
            $tiempos = $this->audits()->get(['auditable_id', 'user_id', 'new_values', 'created_at']);
            $primerEjecucion = $tiempos->first(fn($tiempo) => isset($tiempo->new_values['estado']) ? $tiempo->new_values['estado'] === Ticket::EJECUTANDO : false);
            $finalizacion = $tiempos->first(fn($tiempo) => isset($tiempo->new_values['estado']) ? ($tiempo->new_values['estado'] === Ticket::FINALIZADO_SOLUCIONADO || $tiempo->new_values['estado'] === Ticket::FINALIZADO_SIN_SOLUCION) : false);
            // return $finalizacion ? CarbonInterval::seconds(Carbon::parse($finalizacion->new_values['fecha_hora_finalizado'])->diffInSeconds(Carbon::parse($primerEjecucion->new_values['fecha_hora_ejecucion']))) : null;
            return $finalizacion ? Carbon::parse($finalizacion->new_values['fecha_hora_finalizado'])->diffInSeconds(Carbon::parse($primerEjecucion->new_values['fecha_hora_ejecucion'])) : null;
        } else {
            return null;
        }
    } */

    private function calcularTiempoEfectivoDepartamento()
    {
        // return in_array($this->estado, [Ticket::FINALIZADO_SOLUCIONADO, Ticket::FINALIZADO_SIN_SOLUCION]) ? CarbonInterval::seconds(Carbon::parse($this->fecha_hora_finalizado)->diffInSeconds(Carbon::parse($this->fecha_hora_ejecucion)))->cascade()->forHumans() : null;
        if (in_array($this->estado, [Ticket::FINALIZADO_SOLUCIONADO, Ticket::FINALIZADO_SIN_SOLUCION])) {
            $tiempos = $this->audits()->get(['auditable_id', 'user_id', 'new_values', 'created_at']);
            // Log::channel('testing')->info('Log', compact('tiempos'));
            $primerEjecucion = $tiempos->first(fn($tiempo) => isset($tiempo->new_values['estado']) ? $tiempo->new_values['estado'] === Ticket::EJECUTANDO : false);
            $finalizacion = $tiempos->first(fn($tiempo) => isset($tiempo->new_values['estado']) ? ($tiempo->new_values['estado'] === Ticket::FINALIZADO_SOLUCIONADO || $tiempo->new_values['estado'] === Ticket::FINALIZADO_SIN_SOLUCION) : false);
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
