<?php

namespace Src\App\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\PermisoEmpleado;
use App\Models\RecursosHumanos\NominaPrestamos\Vacacion;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;
use Throwable;

class VacacionService
{
    const DIAS_ANIO = 365;

    public function __construct()
    {
    }

    public static function calcularDiasDeVacacionEmpleadoAntiguo(Empleado $empleado, string $anio_reemplazo)
    {
        $dias_anuales = 15;
        $fecha = Carbon::parse($empleado->fecha_ingreso)->year($anio_reemplazo);
        $dias_transcurridos = Carbon::now()->diffInDays($fecha);

        return (int)floor($dias_anuales / 365 * $dias_transcurridos);
    }

    /**
     * Retorna la cantidad de dias disponibles de vacacion para un empleado cuando aún no ha cumplido 1 año en sus funciones.
     * @param Empleado $empleado
     * @return int cantidad de días disponibles para tomar vacaciones
     */
    public static function calcularDiasDeVacacionEmpleadoNuevo(Empleado $empleado)
    {
        $dias_anuales = 15;
        $hoy = Carbon::now();
        $dias_transcurridos = $hoy->diffInDays($empleado->fecha_ingreso);
        return (int)floor($dias_anuales / 365 * $dias_transcurridos);
    }


    /**
     * Calcula días de vacación para registros de vacacion que se crean pero que aún no tiene un año de ingresado el empleado,
     * entonces debe hacerse el calculo para obtener los días reales en caso de empleados nuevos
     * @param Vacacion $vacacion
     * @return int
     */
    public static function calcularDiasDeVacacionesPeriodoSeleccionado(Vacacion $vacacion)
    {
        // como la vacacion ya se creo nos da 15 días,
        // pero si aún no se ha cumplido todo el periodo debe dar menos dias disponibles
        $fecha_inicio = Carbon::parse($vacacion->empleado->fecha_ingreso);
        $hoy = Carbon::now();
        $dias_transcurridos = $hoy->diffInDays($fecha_inicio);
        Log::channel('testing')->info('Log', ['calcularDiasDeVacacionesPeriodoSeleccionado->dias_transcurridos', $dias_transcurridos, $dias_transcurridos / self::DIAS_ANIO, $dias_transcurridos % self::DIAS_ANIO]);
        $anio_inicio_periodo_vacacion = explode('-', $vacacion->periodo->nombre)[0];
        if ($hoy->year == $anio_inicio_periodo_vacacion) {
            $resto = $dias_transcurridos % self::DIAS_ANIO;
            Log::channel('testing')->info('Log', ['calcularDiasDeVacacionesPeriodoSeleccionado->valores', ($vacacion->dias / self::DIAS_ANIO * $resto) , $vacacion->detalles()->sum('dias_utilizados')]);
            Log::channel('testing')->info('Log', ['calcularDiasDeVacacionesPeriodoSeleccionado->calculo', ($vacacion->dias / self::DIAS_ANIO * $resto) - $vacacion->detalles()->sum('dias_utilizados')]);

            return (int)floor($vacacion->dias / self::DIAS_ANIO * $resto) - $vacacion->detalles()->sum('dias_utilizados');

        } else
            return (int)($vacacion->dias - $vacacion->detalles()->sum('dias_utilizados'));
    }

    /**
     * Metodo para registrar días de vacaciones para un permiso de empleado
     * @param PermisoEmpleado $permiso
     * @return void
     * @throws Throwable
     */
    public function registrarDiasVacacionMediantePermisoEmpleado(PermisoEmpleado $permiso)
    {
        $vacacion = Vacacion::where('empleado_id', $permiso->empleado_id)
            ->where('completadas', false)
            ->first();
        $fecha_hora_inicio = Carbon::parse($permiso->fecha_hora_inicio);
        $fecha_hora_fin = Carbon::parse($permiso->fecha_hora_fin);
        $horas = $fecha_hora_inicio->diffInHours($fecha_hora_fin);
        $calculadoraHoras = new PermisoCalculator();
        $calculadoraHoras->calcularHorasPermiso($fecha_hora_inicio, $fecha_hora_fin);
        $this->registrarDiasVacaciones($permiso->empleado_id,$periodo_id, $permiso, $permiso->fecha_hora_inicio, $permiso->fecha_hora_fin);
    }

    /**
     * Metodo generico para registrar días de vacaciones para una entidad
     * @param int $empleado_id
     * @param int $periodo_id
     * @param Model $entidad
     * @param string|Carbon $fecha_inicio
     * @param string|Carbon $fecha_fin
     * @return void
     * @throws Throwable
     */
    public function registrarDiasVacaciones(int $empleado_id, int $periodo_id, Model $entidad, string|Carbon $fecha_inicio, string|Carbon $fecha_fin)
    {
        try {
            $vacacion = Vacacion::where('empleado_id', $empleado_id)->where('periodo_id', $periodo_id)->first();
            if (!$vacacion)
                $vacacion = self::crearVacacion($empleado_id, $periodo_id);

            if (self::validarDiasDisponibles($empleado_id, $periodo_id, $fecha_inicio, $fecha_fin)) {
                DB::beginTransaction();

                $entidad->detallesVacaciones()->create([
                    'vacacion_id' => $vacacion->id,
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_fin' => $fecha_fin,
                    'dias_utilizados' => Utils::calcularDiasTranscurridos($fecha_inicio, $fecha_fin),
                    'observacion' => 'Registro automático de días de vacaciones a tráves de ' . class_basename($entidad)
                ]);
                DB::commit();

                self::actualizarVacacion($vacacion);
            }

        } catch (Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->error('Log', ['Error en registrarDiasVacaciones:', $th->getLine(), $th->getMessage()]);
            throw $th;
        }
    }


    /**
     * Función para crear un registro de vacación y retorna el registro creado.
     * @param int $empleado_id
     * @param int $periodo_id
     * @return Model|Vacacion
     * @throws Throwable
     */
    public static function crearVacacion(int $empleado_id, int $periodo_id)
    {
        try {
            DB::beginTransaction();
            $vacacion = Vacacion::create([
                'empleado_id' => $empleado_id,
                'periodo_id' => $periodo_id,
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        return $vacacion;
    }

    public static function validarDiasDisponibles(int $empleado_id, int $periodo_id, string|Carbon $fecha_inicio, string|Carbon $fecha_fin)
    {
        $dias = 0;
        $dias_disponibles = 0;

        $vacacion = Vacacion::where('empleado_id', $empleado_id)->where('periodo_id', $periodo_id)->first();
        if ($vacacion) {
            $dias = Utils::calcularDiasTranscurridos($fecha_inicio, $fecha_fin);
            $dias_disponibles = $vacacion->dias - $vacacion->detalles()->sum('dias_utilizados');
        }
        return !($dias > $dias_disponibles);
    }

    /**
     * Calcula si ya se han tomado todos los días de una vacacion para actualizar a completada en caso de alcanzar la cantidad de dias.
     * @param Vacacion $vacacion
     * @return void
     */
    public static function actualizarVacacion(Vacacion $vacacion)
    {
        if ($vacacion->detalles()->sum('dias_utilizados') == $vacacion->dias) {
            $vacacion->completadas = true;
            $vacacion->save();
        }
    }

}
