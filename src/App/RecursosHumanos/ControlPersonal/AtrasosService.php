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
use Illuminate\Support\Facades\Log;
use Throwable;

class AtrasosService
{

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function sincronizarAtrasos()
    {
        $asistenciaService = new AsistenciaService();
        $asistenciaService->sincronizarAsistencias();
        try {
            // En esta etapa trabajamos con los horarios y las marcaciones, para determinar si hubo algun atraso,
            // Primero obtenemos el ultimo registro de atraso para trabajar a partir de allí y no hacer muy pesado, volver a evaluar nuevamente
            $ultimoAtraso = Atraso::latest()->first();
            if ($ultimoAtraso) {
                $marcaciones = Marcacion::where('fecha', '>=', $ultimoAtraso->fecha_atraso)->orderBy('fecha', 'asc')->get();
            } else {
                $marcaciones = Marcacion::orderBy('fecha', 'asc')->get();
            }
            // Se recorre las marcaciones encontradas para verificar si hay atrasos
            foreach ($marcaciones as $marcacion) {
                $empleado = Empleado::find($marcacion->empleado_id);
                // Aqui se busca si el empleado tiene enlazado algun horario diferente para trabajar con aquel
                //                Log::channel('testing')->info('Log', ['sincronizarAtrasos -> marcacion', $marcacion]);
                $dia = Carbon::parse($marcacion->fecha)->locale('es-ES')->dayName;
                $horario = HorarioLaboral::where('dia', 'LIKE', '%' . $dia . '%')->where('activo', true)->first();
                if (!$horario) continue;

                // Tomamos de ejemplo que todos los empleados se ajustan al horario normal que esta definido
                $marcaciones_biometrico = $marcacion->marcaciones;

                foreach ($marcaciones_biometrico as $index => $marcacion_biometrica) {
                    // Log::channel('testing')->info('Log', ['sincronizarAtrasos -> marcacion individual de cada biometrico', $index, $marcacion_biometrica]);
                    $hora_biometrico = Carbon::createFromFormat('H:i:s', $marcacion_biometrica);
                    $hora_entrada = Carbon::parse($horario->hora_entrada)->format('H:i:s');
                    //                    Log::channel('testing')->info('Log', ['sincronizarAtrasos -> $hora_entrada casteada', $hora_entrada, $horario->hora_salida]);
                    //                    $hora_salida = Carbon::createFromFormat('H:i:s', $horario->hora_salida);
                    $hora_salida = Carbon::parse($horario->hora_salida)->format('H:i:s');
                    //                    Log::channel('testing')->info('Log', ['sincronizarAtrasos -> $hora_salida casteada', $hora_salida, $horario->inicio_pausa]);
                    $inicio_pausa = Carbon::parse($horario->inicio_pausa)->format('H:i:s');
                    //                    Log::channel('testing')->info('Log', ['sincronizarAtrasos -> $inicio_pausa casteada', $inicio_pausa, $horario->fin_pausa]);
                    $fin_pausa = Carbon::parse($horario->fin_pausa)->format('H:i:s');
                    //                    Log::channel('testing')->info('Log', ['sincronizarAtrasos -> $fin_pausa casteada', $fin_pausa]);
                    $resultado = [];
                    if ($hora_biometrico->lessThan($hora_entrada)) {
                        $resultado['estado'] = 'Temprano';
                        $resultado['diferencia'] = $hora_biometrico->diffInSeconds($hora_entrada) . ' segundos antes de la hora de entrada o .' . $hora_biometrico->diffInMinutes($hora_entrada) . ' minutos';

                    } elseif ($hora_biometrico->greaterThan($hora_entrada) && $hora_biometrico->lessThan($hora_salida)) {
                        $resultado['estado'] = Atraso::ENTRADA;
                        $resultado['segundos'] = $hora_biometrico->diffInSeconds($hora_entrada);
                        $resultado['diferencia'] = $hora_biometrico->diffInSeconds($hora_entrada) . ' segundos después de la hora de entrada o .' . $hora_biometrico->diffInMinutes($hora_entrada) . ' minutos';
                    }

                    // Evaluar si la hora está cerca del inicio o fin de la pausa
                    if ($hora_biometrico->between($inicio_pausa, $fin_pausa)) {
                        $resultado['estado'] = 'En pausa';
                        //                    } elseif ($hora_biometrico->lessThan($inicio_pausa)) {
                        //                        $resultado['estado'] = 'Antes de la pausa';
                        //                        $resultado['diferencia'] = $hora_biometrico->diffInSeconds($inicio_pausa) . ' segundos antes del inicio de la pausa o .'.$hora_biometrico->diffInMinutes($inicio_pausa).' minutos';
                    } elseif ($hora_biometrico->greaterThan($fin_pausa) && $hora_biometrico->lessThan($hora_salida)) {
                        $resultado['estado'] = Atraso::PAUSA;
                        $resultado['segundos'] = $hora_biometrico->diffInSeconds($fin_pausa);
                        $resultado['diferencia'] = $hora_biometrico->diffInSeconds($fin_pausa) . ' segundos después del fin de la pausa o .' . $hora_biometrico->diffInMinutes($fin_pausa) . ' minutos';
                    }


                    if ($resultado) {
//                        Log::channel('testing')->info('Log', ['sincronizarAtrasos -> resultado', $resultado]);

                        if ($resultado['estado'] == Atraso::ENTRADA || $resultado['estado'] == Atraso::PAUSA) {
                            $this->guardarAtraso($resultado['estado'], $resultado['segundos'], $marcacion);
                        }
                    }
                }
            }
        } catch (Exception $e) {
            Log::channel('testing')->error('Log', ['error en sincronizarAtrasos ', $e->getLine(), $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * @throws Throwable
     */
    public function guardarAtraso(string $ocurrencia, int $segundos, Marcacion $marcacion)
    {
        try {
            DB::beginTransaction();
            //buscamos si ya existe un atraso para los argumentos proporcionados, en caso de existir, omitimos
            $atraso = Atraso::firstOrCreate([
                'empleado_id' => $marcacion->empleado_id,
                'marcacion_id' => $marcacion->id,
                'ocurrencia' => $ocurrencia,
                'fecha_atraso' => $marcacion->fecha,
                'segundos_atraso' => $segundos
            ], []);

            if ($atraso->wasRecentlyCreated) {
                // Se envia el evento de notificacion al jefe inmediato del empleado cuyo atraso se registra
                $empleado = Empleado::find($marcacion->empleado_id);
                if ($empleado) {
                    event(new NotificarAtrasoEmpleado($atraso));
                    event(new NotificarAtrasoJefeInmediato($atraso));
                }
            }

            DB::commit();
        } catch (Exception $e) {
            Log::channel('testing')->error('Log', ['error en guardar Atraso', $e->getLine(), $e->getMessage()]);
            DB::rollBack();
            throw $e;
        }
    }
}
