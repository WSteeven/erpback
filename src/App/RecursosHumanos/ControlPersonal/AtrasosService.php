<?php

namespace Src\App\RecursosHumanos\ControlPersonal;

use App\Events\ControlPersonal\NotificarAtrasoEmpleado;
use App\Events\ControlPersonal\NotificarAtrasoJefeInmediato;
use App\Models\ControlPersonal\Atraso;
use App\Models\ControlPersonal\Marcacion;
use App\Models\Empleado;
use App\Models\RecursosHumanos\ControlPersonal\HorarioLaboral;
use App\Models\RecursosHumanos\NominaPrestamos\PermisoEmpleado;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

class AtrasosService
{
    /**
     * Sincroniza atrasos y anomalías con base en las marcaciones biométricas.
     *
     * @throws Exception
     * @throws Throwable
     */
    public function sincronizarAtrasos()
    {
        $asistenciaService = new AsistenciaService();
        $asistenciaService->sincronizarAsistencias();

        try {
            $ultimoAtraso = Atraso::latest()->first();
            $marcaciones = $ultimoAtraso
                ? Marcacion::where('fecha', '>=', $ultimoAtraso->fecha_atraso)->orderBy('fecha', 'asc')->get()
                : Marcacion::orderBy('fecha', 'asc')->get();

            foreach ($marcaciones as $marcacion) {
                $empleado = Empleado::find($marcacion->empleado_id);
                if (!$empleado) {
                    continue;
                }

                // Obtener horario del día
                $dia = Carbon::parse($marcacion->fecha)->locale('es')->dayName;
                $horario = HorarioLaboral::where('dia', 'LIKE', '%' . $dia . '%')
                    ->where('activo', true)
                    ->first();

                if (!$horario) {
                    continue;
                }

                // Crear objetos Carbon con la fecha completa para comparaciones precisas
                $fechaMarcacion = Carbon::parse($marcacion->fecha);

                // Extraer solo la parte de tiempo de los horarios (por si vienen con fecha)
                $horaEntradaStr = Carbon::parse($horario->hora_entrada)->format('H:i:s');
                $inicioPausaStr = Carbon::parse($horario->inicio_pausa)->format('H:i:s');
                $finPausaStr = Carbon::parse($horario->fin_pausa)->format('H:i:s');
                $horaSalidaStr = Carbon::parse($horario->hora_salida)->format('H:i:s');

                // Combinar con la fecha de la marcación
                $horaEntrada = Carbon::parse($fechaMarcacion->format('Y-m-d') . ' ' . $horaEntradaStr);
                $inicioPausa = Carbon::parse($fechaMarcacion->format('Y-m-d') . ' ' . $inicioPausaStr);
                $finPausa = Carbon::parse($fechaMarcacion->format('Y-m-d') . ' ' . $finPausaStr);
                $horaSalida = Carbon::parse($fechaMarcacion->format('Y-m-d') . ' ' . $horaSalidaStr);

                $marcacionesBiometrico = $marcacion->marcaciones;
                $totalMarcaciones = count($marcacionesBiometrico);

                // Verificar si existe permiso aprobado o pendiente para esta fecha
                $permiso = PermisoEmpleado::where('empleado_id', $empleado->id)
                    ->whereIn('estado_permiso_id', [
                        PermisoEmpleado::APROBADO,
                        PermisoEmpleado::PENDIENTE // Incluir pendientes también
                    ])
                    ->where(function ($q) use ($marcacion) {
                        $q->whereDate('fecha_hora_inicio', '<=', $marcacion->fecha)
                          ->whereDate('fecha_hora_fin', '>=', $marcacion->fecha);
                    })
                    ->first();

                // CASO: Sin marcaciones en el día
                if ($totalMarcaciones === 0) {
                    if (!$permiso) {
                        $this->guardarAnomalia($empleado, $marcacion, 'SIN MARCACIONES',
                            'No existen registros biométricos para este día.');
                    } else {
                        Log::channel('testing')->info('Sin marcaciones pero tiene permiso.', [
                            'empleado' => $empleado->nombres,
                            'fecha' => $marcacion->fecha,
                            'permiso' => $permiso->justificacion ?? 'Sin justificación'
                        ]);
                    }
                    continue;
                }

                // CASO: Una sola marcación
                if ($totalMarcaciones === 1) {
                    if (!$permiso) {
                        $this->guardarAnomalia($empleado, $marcacion, 'UNA MARCACION',
                            'Solo una marcación registrada: ' . $marcacionesBiometrico[0]);
                    } else {
                        Log::channel('testing')->info('Una marcación pero tiene permiso.', [
                            'empleado' => $empleado->nombres,
                            'fecha' => $marcacion->fecha,
                            'hora' => $marcacionesBiometrico[0],
                            'permiso' => $permiso->justificacion ?? 'Sin justificación'
                        ]);
                    }
                    continue;
                }

                // Convertir marcaciones a objetos Carbon y ordenar
                $horas = collect($marcacionesBiometrico)
                    ->map(function($h) use ($fechaMarcacion) {
                        return Carbon::parse($fechaMarcacion->format('Y-m-d') . ' ' . $h);
                    })
                    ->sort()
                    ->values();

                $primeraHora = $horas->first();
                $ultimaHora = $horas->last();

                // Separar marcaciones en periodos
                $marcacionesAntesPausa = $horas->filter(fn($h) => $h->lessThan($inicioPausa));
                $marcacionesDespuesPausa = $horas->filter(fn($h) => $h->greaterThanOrEqualTo($inicioPausa));

                // VALIDACIÓN 1: ATRASO EN HORA DE ENTRADA
                if ($marcacionesAntesPausa->isNotEmpty()) {
                    $primeraEntrada = $marcacionesAntesPausa->first();

                    if ($primeraEntrada->greaterThan($horaEntrada)) {
                        $segundos = $primeraEntrada->diffInSeconds($horaEntrada);
                        $this->guardarAtraso(Atraso::ENTRADA, $segundos, $marcacion);

                        Log::channel('testing')->info('Atraso registrado en hora de entrada.', [
                            'empleado' => $empleado->nombres,
                            'hora_marcacion' => $primeraEntrada->format('H:i:s'),
                            'hora_entrada' => $horaEntrada->format('H:i:s'),
                            'segundos' => $segundos,
                        ]);
                    }
                } else {
                    // No hay marcaciones antes de pausa
                    if (!$permiso) {
                        $this->guardarAnomalia($empleado, $marcacion, 'SIN ENTRADA',
                            'No se detectó marcación de entrada antes del almuerzo.');
                    }
                    continue;
                }

                // VALIDACIÓN 2: ATRASO EN REGRESO DE PAUSA
                if ($marcacionesDespuesPausa->isNotEmpty()) {
                    $primeraRegresoPausa = $marcacionesDespuesPausa->first();

                    // Solo evaluar atraso si la marcación está en horario laboral
                    if ($primeraRegresoPausa->lessThan($horaSalida) &&
                        $primeraRegresoPausa->greaterThan($finPausa)) {

                        $segundos = $primeraRegresoPausa->diffInSeconds($finPausa);
                        $this->guardarAtraso(Atraso::PAUSA, $segundos, $marcacion);

                        Log::channel('testing')->info('Atraso registrado en fin de pausa.', [
                            'empleado' => $empleado->nombres,
                            'hora_marcacion' => $primeraRegresoPausa->format('H:i:s'),
                            'hora_fin_pausa' => $finPausa->format('H:i:s'),
                            'segundos' => $segundos,
                        ]);
                    }
                } else {
                    // No hay marcaciones después de la pausa
                    if ($permiso) {
                        // Verificar si el permiso cubre la tarde
                        $inicioPermiso = Carbon::parse($permiso->fecha_hora_inicio);
                        $finPermiso = Carbon::parse($permiso->fecha_hora_fin);

                        if ($inicioPermiso->lessThanOrEqualTo($finPausa)) {
                            Log::channel('testing')->info('Sin marcación de regreso, pero tiene permiso que cubre la tarde.', [
                                'empleado' => $empleado->nombres,
                                'fecha' => $marcacion->fecha,
                                'permiso_inicio' => $inicioPermiso->format('H:i:s'),
                                'permiso_fin' => $finPermiso->format('H:i:s'),
                            ]);
                        } else {
                            $this->guardarAnomalia($empleado, $marcacion, 'SIN FIN PAUSA',
                                'No se detectó marcación de regreso después del almuerzo. Permiso: ' .
                                $inicioPermiso->format('H:i') . ' - ' . $finPermiso->format('H:i'));
                        }
                    } else {
                        $this->guardarAnomalia($empleado, $marcacion, 'SIN FIN PAUSA',
                            'No se detectó marcación de regreso después del almuerzo.');
                    }
                }

                // CASO ESPECIAL: Solo 2 marcaciones
                if ($totalMarcaciones === 2 && !$permiso) {
                    // Verificar si ambas marcaciones son en la mañana o si falta la tarde
                    if ($marcacionesDespuesPausa->isEmpty()) {
                        $this->guardarAnomalia($empleado, $marcacion, 'SALIDA TEMPRANA',
                            'Solo hay marcaciones en la mañana. Última marcación: ' .
                            $ultimaHora->format('H:i:s'));
                    }
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

    /**
     * Registra un atraso evitando duplicados y enviando notificaciones.
     *
     * @param string $ocurrencia Tipo de atraso (ENTRADA o PAUSA)
     * @param int $segundos Segundos de atraso
     * @param Marcacion $marcacion Objeto de marcación
     * @throws Exception
     */
    public function guardarAtraso(string $ocurrencia, int $segundos, Marcacion $marcacion)
    {
        try {
            DB::beginTransaction();

            // Verificar si ya existe este atraso
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
     * Registra anomalías como atrasos de tipo especial.
     *
     * @param Empleado $empleado
     * @param Marcacion $marcacion
     * @param string $tipo
     * @param string $detalle
     */
    private function guardarAnomalia(Empleado $empleado, Marcacion $marcacion, string $tipo, string $detalle)
    {
        try {
            DB::beginTransaction();

            // Verificar si ya existe esta anomalía
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
    }
}
