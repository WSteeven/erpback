<?php

namespace Src\App\RecursosHumanos\NominaPrestamos;

use App\Http\Resources\RecursosHumanos\NominaPrestamos\PlanVacacionResource;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\VacacionResource;
use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\Periodo;
use App\Models\RecursosHumanos\NominaPrestamos\PermisoEmpleado;
use App\Models\RecursosHumanos\NominaPrestamos\PlanVacacion;
use App\Models\RecursosHumanos\NominaPrestamos\Vacacion;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
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
     * Obtiene los días de antiguedad de un empleado, en base a los registros de vacaciones.
     * @param int $empleado_id
     * @return int|mixed
     */
    public static function obtenerDiasVacacionSegunAntiguedadEmpleado(int $empleado_id)
    {
        /* TODO: Léase: Art 69 del Código del Trabajo */

        if (Vacacion::where('empleado_id', $empleado_id)->count() >= 5) {
            $dias_ultima_vacacion = Vacacion::where('empleado_id', $empleado_id)->max('dias');
            return ($dias_ultima_vacacion + 1) < 30 ? $dias_ultima_vacacion + 1 : 30;
        }
        return 15;
    }


    /**
     * Calcula días de vacación para registros de vacacion que se crean pero que aún no tiene un año de ingresado el empleado,
     * entonces debe hacerse el calculo para obtener los días reales en caso de empleados nuevos o en caso de registros de vacacion para un periodo que apenas estan empezando
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
//        Log::channel('testing')->info('Log', ['calcularDiasDeVacacionesPeriodoSeleccionado->dias_transcurridos', $dias_transcurridos, $dias_transcurridos / self::DIAS_ANIO, $dias_transcurridos % self::DIAS_ANIO]);
        $anio_inicio_periodo_vacacion = explode('-', $vacacion->periodo->nombre)[0];
        if ($hoy->year == $anio_inicio_periodo_vacacion) {
            $resto = $dias_transcurridos % self::DIAS_ANIO;
//            Log::channel('testing')->info('Log', ['calcularDiasDeVacacionesPeriodoSeleccionado->valores', ($vacacion->dias / self::DIAS_ANIO * $resto), $vacacion->detalles()->sum('dias_utilizados')]);
//            Log::channel('testing')->info('Log', ['calcularDiasDeVacacionesPeriodoSeleccionado->calculo', ($vacacion->dias / self::DIAS_ANIO * $resto) - $vacacion->detalles()->sum('dias_utilizados')]);

            return (int)floor($vacacion->dias / self::DIAS_ANIO * $resto) - $vacacion->detalles()->sum('dias_utilizados');

        } else
            return (int)($vacacion->dias - $vacacion->detalles()->sum('dias_utilizados'));
    }

    public static function reporte(Request $request)
    {
        switch ($request->tipo) {
            case 'PLAN_VACACIONES':
                $results = PlanVacacion::where('periodo_id', $request->periodo)
                    ->when(!$request->todos, function ($query) use ($request) {
                        $query->where('empleado_id', $request->empleado_id);
                    })->get();
//                Log::channel('testing')->info('Log', ['Results planes_vacaciones', $results]);
                return PlanVacacionResource::collection($results);
            case 'VACACIONES_PENDIENTES':
                $results = Vacacion::where('completadas', false)->get();
                return VacacionResource::collection($results);
            default:
                $results = Vacacion::where('periodo_id', $request->periodo)
                    ->when(!$request->todos, function ($query) use ($request) {
                        $query->where('empleado_id', $request->empleado_id);
                    })->whereHas('detalles')
                    ->get();
//                Log::channel('testing')->info('Log', ['Results vacaciones', $results]);
                return VacacionResource::collection($results);
        }
    }

    private function calcularPeriodo(Empleado $empleado)
    {
        $empleado->fecha_ingreso = Carbon::parse($empleado->fecha_ingreso);
        $anio = explode('-', $empleado->fecha_ingreso)[0];
        $periodo = Periodo::where('nombre', 'LIKE', $anio . '%')->first();
        if ($periodo) return $periodo->id;
        return -1;
    }

    /**
     * Metodo para registrar días de vacaciones para un permiso de empleado
     * @param PermisoEmpleado $permiso
     * @return void
     * @throws Throwable
     */
    public function registrarDiasVacacionMediantePermisoEmpleado(PermisoEmpleado $permiso)
    {
        if (!Vacacion::where('empleado_id', $permiso->empleado_id)->exists()) {
            // Si no hay ningun registro de vacacion, supongamos
            $periodo_id = $this->calcularPeriodo($permiso->empleado);
            if ($periodo_id > 0) $this->registrarDiasVacaciones($permiso->empleado_id, $periodo_id, $permiso, $permiso->fecha_hora_inicio, $permiso->fecha_hora_fin);
        } else {
            $vacacion = Vacacion::where('empleado_id', $permiso->empleado_id)
                ->where('completadas', false)
                ->first();
            //recorremos las vacaciones, hasta encontrar una que si tenga dias disponibles
            while ($vacacion && !self::validarDiasDisponibles($vacacion->empleado_id, $vacacion->periodo_id, $permiso->fecha_hora_inicio, $permiso->fecha_hora_fin)) {
                $vacacion = Vacacion::where('empleado_id', $permiso->empleado_id)
                    ->where('completadas', false)
                    ->where('id', $vacacion->id)->first();
            }

            if ($vacacion) {
                $this->registrarDiasVacaciones($permiso->empleado_id, $vacacion->periodo_id, $permiso, $permiso->fecha_hora_inicio, $permiso->fecha_hora_fin);
            }
        }
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
//        Log::channel('testing')->info('Log', ['registrarDiasVacaciones args?', $empleado_id, $periodo_id, $entidad, $fecha_inicio, $fecha_fin]);
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
        /*TODO: Verificar la antiguedad del empleado para aumentar un día más a partir de 5 años de antiguedad*/
        try {
            $dias = self::obtenerDiasVacacionSegunAntiguedadEmpleado($empleado_id);
            DB::beginTransaction();
            $vacacion = Vacacion::create([
                'empleado_id' => $empleado_id,
                'periodo_id' => $periodo_id,
                'dias' => $dias
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
//            $dias_disponibles = $vacacion->dias - $vacacion->detalles()->sum('dias_utilizados');
            $dias_disponibles = self::calcularDiasDeVacacionesPeriodoSeleccionado($vacacion);
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

    public static function calcularMontoPagarVacaciones(Vacacion $vacacion)
    {
        /**
         * Formula: sueldo * 12 / 365 * dias de vacaciones
         */
        $dias_disponibles = VacacionService::calcularDiasDeVacacionesPeriodoSeleccionado($vacacion);
        $valor = $vacacion->empleado->salario * 12 / 365 * $dias_disponibles;
        return round($valor, 2);
    }

}
