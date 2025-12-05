<?php

namespace Src\App\RecursosHumanos\ControlPersonal;

use App\Events\ControlPersonal\NotificarAtrasoEmpleado;
use App\Events\ControlPersonal\NotificarAtrasoJefeInmediato;
use App\Models\ControlPersonal\Atraso;
use App\Models\ControlPersonal\Marcacion;
use App\Models\Empleado;
use App\Models\RecursosHumanos\ControlPersonal\HorarioLaboral;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;
use Throwable;

class AtrasosService
{
    /**
     * Sincroniza todos los atrasos del mes actual, de entrada y regreso de pausa
     * con base en las marcaciones biométricas.
     * @throws Throwable
     */
    public function sincronizarAtrasos()
    {
        $asistenciaService = new AsistenciaService();
        $asistenciaService->sincronizarAsistencias();

        try {
            $ultimoAtraso = Atraso::latest()->first();
            $marcaciones = $ultimoAtraso
                ? Marcacion::where('fecha', '>=', Carbon::now()->startOfMonth())->orderBy('fecha', 'asc')->get()
                : Marcacion::orderBy('fecha', 'asc')->get();

            foreach ($marcaciones as $marcacion) {
                $fechaMarcacion = Carbon::parse($marcacion->fecha);
                $empleado = Empleado::find($marcacion->empleado_id);
                if (!$empleado) continue;

                $dia = $fechaMarcacion->locale('es')->dayName; //dia en español

                $horarios = $this->obtenerHorarioLaboralPorEmpleadoYDia($empleado, Utils::normalizarDiaSemana($dia));
                if ($horarios->isEmpty()) continue;

                // Si hay múltiples horarios, se recorre cada ambos para calcular los atrasos en base a cada uno
                foreach ($horarios as $horario) {
                    $horaEntrada = Carbon::parse($fechaMarcacion->format('Y-m-d') . ' ' . Carbon::parse($horario->hora_entrada)->format('H:i:s'));
                    $finPausa = $horario->fin_pausa ? Carbon::parse($fechaMarcacion->format('Y-m-d') . ' ' . Carbon::parse($horario->fin_pausa)->format('H:i:s')) : null;
                    $horaSalida = Carbon::parse($fechaMarcacion->format('Y-m-d') . ' ' . Carbon::parse($horario->hora_salida)->format('H:i:s'));

                    $marcacionesBiometrico = $marcacion->marcaciones;
                    $totalMarcaciones = 0;
                    $horas = collect();

                    foreach ($marcacionesBiometrico as $marcacionBiometrico) {
                        foreach ($marcacionBiometrico as  $hora) {
                            if ($hora && trim($hora) !== '') {
                                $horas->push(Carbon::parse($fechaMarcacion->format('Y-m-d') . ' ' . $hora));
                                $totalMarcaciones++;
                            }
                        }
                    }

                    // Saltar días sin marcaciones
                    if ($totalMarcaciones === 0) continue;

                    // Ordenar las horas
                    $horas = $horas->sort()->values();

                    $primeraHora = $horas->first();

                    // VALIDACIÓN: ATRASO EN ENTRADA
                    if ($primeraHora->greaterThan($horaEntrada)) {
                        $segundos = $primeraHora->diffInSeconds($horaEntrada);
                        $this->guardarAtraso(Atraso::ENTRADA, $segundos, $marcacion);

                        Log::channel('testing')->info('Atraso registrado en hora de entrada.', [
                            'empleado' => $empleado->nombres,
                            'hora_marcacion' => $primeraHora->format('H:i:s'),
                            'hora_entrada' => $horaEntrada->format('H:i:s'),
                            'segundos' => $segundos,
                            'minutos' => round($segundos / 60, 2)
                        ]);
                    }

                    // VALIDACIÓN: ATRASO EN REGRESO DE PAUSA
                    $marcacionesDespuesPausa = $horas->filter(fn($h) => $h->greaterThan($finPausa));
                    if ($marcacionesDespuesPausa->isNotEmpty()) {
                        $primeraRegresoPausa = $marcacionesDespuesPausa->first();

                        if ($primeraRegresoPausa->lessThan($horaSalida) && $primeraRegresoPausa->greaterThan($finPausa)) {
                            $segundos = $primeraRegresoPausa->diffInSeconds($finPausa);
                            $this->guardarAtraso(Atraso::PAUSA, $segundos, $marcacion);

                            Log::channel('testing')->info('Atraso registrado en regreso de pausa.', [
                                'empleado' => $empleado->nombres,
                                'hora_marcacion' => $primeraRegresoPausa->format('H:i:s'),
                                'hora_fin_pausa' => $finPausa->format('H:i:s'),
                                'segundos' => $segundos,
                                'minutos' => round($segundos / 60, 2)
                            ]);
                        }
                    }

                    // Borrar atrasos mal registrados
                    $this->borrarAtrasosMalRegistrados($empleado, $marcacion, $horario, $horas);
                }
            }
            Log::channel('testing')->info('Sincronización de atrasos completada correctamente.');
        } catch (Exception $e) {
            Log::channel('testing')->error('Error en sincronizarAtrasos', [
                'line' => $e->getLine(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function obtenerHorarioLaboralPorEmpleadoYDia(Empleado $empleado, string $dia)
    {
        $diaNormalizado = Utils::normalizarDiaSemana($dia);

        // Si el empleado no tiene un horario asignado, buscar en el horario normal
        if ($empleado->horarioEmpleado->isEmpty()) {
            $horario = HorarioLaboral::where('nombre', HorarioLaboral::HORARIO_NORMAL)
                ->whereJsonContains('dias', $diaNormalizado)->where('activo', true)->first();
            return collect($horario ? [$horario] : []);
        }
        // Buscar en los horarios asignados al empleado
        return $empleado->horarioEmpleado
            ->map(fn($horarioEmpleado) => $horarioEmpleado->horarioLaboral)
            ->filter(fn($horarioLaboral) => $horarioLaboral && in_array($diaNormalizado, $horarioLaboral->dias) && $horarioLaboral->activo)
            ->values();

    }

    /**
     * @throws Throwable
     */
    private function borrarAtrasosMalRegistrados(Empleado $empleado, Marcacion $marcacion, HorarioLaboral $horario, Collection $horasOrdenadas)
    {
        try {

            $fechaMarcacion = Carbon::parse($marcacion->fecha);
            $primeraMarcacion = $horasOrdenadas->first();
            $horaEntrada = Carbon::parse($fechaMarcacion->format('Y-m-d') . ' ' . Carbon::parse($horario->hora_entrada)->format('H:i:s'));
            $finPausa = $horario->fin_pausa ? Carbon::parse($fechaMarcacion->format('Y-m-d') . ' ' . Carbon::parse($horario->fin_pausa)->format('H:i:s')) : null;
            $horaSalida = Carbon::parse($fechaMarcacion->format('Y-m-d') . ' ' . Carbon::parse($horario->hora_salida)->format('H:i:s'));

            // Borrar atraso de entrada si la primera marcación es antes o igual a la hora de entrada
            if ($primeraMarcacion->lessThanOrEqualTo($horaEntrada)) {
                $atraso = Atraso::where('empleado_id', $empleado->id)
                    ->where('marcacion_id', $marcacion->id)
                    ->where('ocurrencia', Atraso::ENTRADA)
                    ->first();
                $atraso?->notificaciones()->delete();
                $atraso?->delete();
                Log::channel('testing')->info("Atraso de ENTRADA eliminado junto a sus notificaciones (ya no aplica).", [
                    'empleado' => $empleado->nombres,
                    'fecha' => $fechaMarcacion,
                    'primer_marcaje' => $primeraMarcacion,
                    'hora_entrada' => $horaEntrada->toDateTimeString()
                ]);
            }
            // Borrar atraso de regreso de pausa si la primera marcación después de la pausa es antes o igual al fin de pausa
            if ($finPausa) {
                $marcacionesDespuesPausa = $horasOrdenadas->filter(fn($h) => $h->greaterThan($finPausa));
                $primerRegresoPausa = $marcacionesDespuesPausa->isNotEmpty() ? $marcacionesDespuesPausa->first() : null;
                $hayAtrasoRegresoPausa = $primerRegresoPausa && $primerRegresoPausa->greaterThan($finPausa) && $primerRegresoPausa->lessThan($horaSalida);

                // Si no hay atraso en regreso de pausa, eliminar el registro si existe
                if (!$hayAtrasoRegresoPausa) {
                    $atrasoPausa = Atraso::where('empleado_id', $empleado->id)
                        ->where('marcacion_id', $marcacion->id)
                        ->where('ocurrencia', Atraso::PAUSA)
                        ->first();
                    $atrasoPausa?->notificaciones()->delete();
                    $atrasoPausa?->delete();
                    Log::channel('testing')->info("Atraso de REGRESO DE PAUSA eliminado junto a sus notificaciones (ya no aplica).", [
                        'empleado' => $empleado->nombres,
                        'fecha' => $fechaMarcacion,
                        'primer_regreso_pausa' => $primerRegresoPausa,
                        'fin_pausa' => $finPausa->toDateTimeString()
                    ]);
                }
            }
        } catch (Throwable $e) {
            Log::channel('testing')->error("Error en borrarAtrasosMalRegistrados", [
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }


    /**
     * Verifica al final del día si los empleados tienen las 4 marcaciones esperadas.
     * Idealmente se ejecuta mediante un cron a las 23:59:59.
     */
    /*public function verificarMarcacionesCompletasDelDia()
    {
        try {
            $fechaHoy = Carbon::today()->format('Y-m-d');
            $marcacionesDelDia = Marcacion::where('fecha', $fechaHoy)->get();

            foreach ($marcacionesDelDia as $marcacion) {
                $empleado = Empleado::find($marcacion->empleado_id);
                if (!$empleado) continue;

                $dia = Carbon::parse($fechaHoy)->locale('es')->dayName;
                $horario = HorarioLaboral::where('dia', 'LIKE', '%' . $dia . '%')
                    ->where('activo', true)
                    ->first();

                if (!$horario) continue;

                $fechaBase = Carbon::parse($fechaHoy);
                $horaEntrada = Carbon::parse($fechaBase->format('Y-m-d') . ' ' . Carbon::parse($horario->hora_entrada)->format('H:i:s'));
                $inicioPausa = Carbon::parse($fechaBase->format('Y-m-d') . ' ' . Carbon::parse($horario->inicio_pausa)->format('H:i:s'));
                $finPausa = Carbon::parse($fechaBase->format('Y-m-d') . ' ' . Carbon::parse($horario->fin_pausa)->format('H:i:s'));
                $horaSalida = Carbon::parse($fechaBase->format('Y-m-d') . ' ' . Carbon::parse($horario->hora_salida)->format('H:i:s'));

                $horas = collect($marcacion->marcaciones)
                    ->map(fn($h) => Carbon::parse($fechaBase->format('Y-m-d') . ' ' . $h))
                    ->sort()
                    ->values();

                if ($horas->isEmpty()) continue;

                // Verificación por bloques
                $faltantes = [];

                // Entrada
                if (!$horas->first(fn($h) => $h->lessThanOrEqualTo($inicioPausa))) {
                    $faltantes[] = 'entrada';
                }

                // Salida a pausa
                $salidaPausaExiste = $horas->first(fn($h) => $h->between($horaEntrada, $inicioPausa->copy()->addMinutes(30)));
                if (!$salidaPausaExiste) {
                    $faltantes[] = 'salida a pausa';
                }

                // Regreso de pausa
                if (!$horas->first(fn($h) => $h->greaterThanOrEqualTo($finPausa))) {
                    $faltantes[] = 'regreso de pausa';
                }

                // Salida final
                if (!$horas->first(fn($h) => $h->greaterThanOrEqualTo($horaSalida))) {
                    $faltantes[] = 'salida final';
                }

                if (!empty($faltantes)) {
                    $detalle = 'Faltan marcaciones: ' . implode(', ', $faltantes);
                    $this->guardarAnomalia($empleado, $marcacion, 'FALTAN MARCACIONES', $detalle);

                    Log::channel('testing')->warning('Faltan marcaciones detectadas.', [
                        'empleado' => $empleado->nombres,
                        'fecha' => $fechaHoy,
                        'faltantes' => $faltantes,
                        'total_marcaciones' => count($horas)
                    ]);
                } else {
                    Log::channel('testing')->info('Marcaciones completas para el día.', [
                        'empleado' => $empleado->nombres,
                        'fecha' => $fechaHoy,
                        'total_marcaciones' => count($horas)
                    ]);
                }
            }

            Log::channel('testing')->info('Verificación de marcaciones completas finalizada.');
        } catch (Exception $e) {
            Log::channel('testing')->error('Error en verificarMarcacionesCompletasDelDia', [
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
            throw $e;
        }
    }*/

    /**
     * Registra un atraso evitando duplicados y enviando notificaciones.
     * @throws Throwable
     */
    public function guardarAtraso(string $ocurrencia, int $segundos, Marcacion $marcacion)
    {
        try {
            DB::beginTransaction();

            $existe = Atraso::where('empleado_id', $marcacion->empleado_id)
                ->where('fecha_atraso', $marcacion->fecha)
                ->where('ocurrencia', $ocurrencia)
                ->exists();

            if ($existe) {
                DB::rollBack();
                return;
            }

            $atraso = Atraso::create([
                'empleado_id' => $marcacion->empleado_id,
                'marcacion_id' => $marcacion->id,
                'ocurrencia' => $ocurrencia,
                'fecha_atraso' => $marcacion->fecha,
                'segundos_atraso' => $segundos,
                'justificado' => false,
                'revisado' => false,
            ]);

            if ($atraso && $atraso->wasRecentlyCreated) {
                $empleado = Empleado::find($marcacion->empleado_id);
                if ($empleado) {
                    event(new NotificarAtrasoEmpleado($atraso));
                    event(new NotificarAtrasoJefeInmediato($atraso));
                }
            }

            DB::commit();
        } catch (Exception $e) {
            Log::channel('testing')->error('Error al guardar atraso.', [
                'line' => $e->getLine(),
                'message' => $e->getMessage(),
            ]);
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Registra anomalías (como faltantes de marcaciones).
     */
    /*private function guardarAnomalia(Empleado $empleado, Marcacion $marcacion, string $tipo, string $detalle)
    {
        try {
            DB::beginTransaction();

            $existe = Atraso::where('empleado_id', $empleado->id)
                ->where('fecha_atraso', $marcacion->fecha)
                ->where('ocurrencia', 'ANOMALIA: ' . $tipo)
                ->exists();

            if ($existe) {
                DB::rollBack();
                return;
            }

            Atraso::create([
                'empleado_id' => $empleado->id,
                'marcacion_id' => $marcacion->id,
                'fecha_atraso' => $marcacion->fecha,
                'ocurrencia' => 'ANOMALIA: ' . $tipo,
                'segundos_atraso' => 0,
                'justificado' => false,
                'justificacion' => $detalle,
                'revisado' => false,
            ]);

            DB::commit();

            Log::channel('testing')->warning('Anomalía registrada.', [
                'empleado' => $empleado->nombres,
                'fecha' => $marcacion->fecha,
                'tipo' => $tipo,
                'detalle' => $detalle,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->error('Error al guardar anomalía.', [
                'line' => $e->getLine(),
                'message' => $e->getMessage(),
            ]);
        }
    }*/
}
