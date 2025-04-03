<?php

namespace Src\App\FondosRotativos;

use App\Models\FondosRotativos\AjusteSaldoFondoRotativo;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\FondosRotativos\Saldo\Acreditaciones;
use App\Models\FondosRotativos\Saldo\EstadoAcreditaciones;
use App\Models\FondosRotativos\Saldo\Saldo;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Saldo\Transferencias;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;

class SaldoService
{
//    private $fechaInicio;
//    private $fechaFin;
    public const INGRESO = 'INGRESO';
    public const EGRESO = 'EGRESO';
    public const AJUSTE = 'AJUSTE';
    public const ANULACION = 'ANULACION';

    public function __construct()
    {
    }
//    public function setFechaInicio($fechaInicio)
//    {
//        $this->fechaInicio = $fechaInicio;
//    }
//    public function setFechaFin($fechaFin)
//    {
//        $this->fechaFin = $fechaFin;
//    }
//    public function setIdEmpleado($idEmpleado)
//    {
//        $this->idEmpleado = $idEmpleado;
//    }
    public function getFechaMesAnterior($fecha = null): array
    {
        $fechaInicio = new Carbon($fecha);
        return [
            'inicio' => $fechaInicio->copy()->subMonth()->startOfMonth()->format('d-m-Y'),
            'fin' => $fechaInicio->copy()->subMonth()->endOfMonth()->format('d-m-Y'),
        ];
    }

    public function SaldoEstadoCuenta($fechaInicio = null, $fechaFin = null, $id_empleado = null)
    {
        // Si las fechas no se proporcionan, usa las propiedades de la clase
//        $fechaInicio = $fechaInicio ?? $this->fechaInicio;
//        $fechaFin = $fechaFin ?? $this->fechaFin;

        $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->format('Y-m-d');

        // Obtén el saldo anterior de manera más eficiente
        $saldo_anterior = SaldoGrupo::where('id_usuario', $id_empleado)
            ->where('fecha', '<=', Carbon::parse($fechaInicio)->subDay())
            ->latest('created_at')->first();

        $saldo_anterior = $saldo_anterior ? $saldo_anterior->saldo_actual : 0;

        // Calcula las sumas directamente en las consultas
        $acreditaciones = Acreditaciones::where('id_usuario', $id_empleado)
            ->where('id_estado', EstadoAcreditaciones::REALIZADO)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->sum('monto');

        $transferencia_enviadas = Transferencias::where('usuario_envia_id', $id_empleado)
            ->where('estado', 1)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->sum('monto');

        $transferencia_recibida = Transferencias::where('usuario_recibe_id', $id_empleado)
            ->where('estado', 1)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->sum('monto');

        $gastos = Gasto::where('estado', 1)
            ->where('id_usuario', $id_empleado)
            ->whereBetween('fecha_viat', [$fechaInicio, $fechaFin])
            ->sum('total');

        // Calcula el saldo actual de manera más eficiente
        return $saldo_anterior + ($acreditaciones - $transferencia_enviadas + $transferencia_recibida - $gastos);
    }

    /**
     * Obtiene las transferencias enviadas o recibidas por un empleado en un rango de fecha determinado.
     *
     * @param int $empleado_id
     * @param $fecha_inicio
     * @param $fecha_fin
     * @param bool $enviada
     * @param bool $incluir_anuladas
     * @return mixed
     */
    public function obtenerTransferencias(int $empleado_id, $fecha_inicio, $fecha_fin, bool $enviada = true, bool $incluir_anuladas = false)
    {
//        Log::channel('testing')->info('Log', ['fecha fin es', $fecha_fin]);
        $campo_usuario = $enviada ? 'usuario_envia_id' : 'usuario_recibe_id';
        return Transferencias::where($campo_usuario, $empleado_id)
            ->with('empleadoRecibe', 'empleadoEnvia')
            ->whereIn('estado', $incluir_anuladas
                ? [Transferencias::APROBADO, Transferencias::ANULADO]
                : [Transferencias::APROBADO])
            ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
            ->get();
    }

    public function obtenerAjustesSaldos(Carbon $fecha_inicio, Carbon $fecha_fin, $empleado, $tipo = AjusteSaldoFondoRotativo::INGRESO)
    {
       return  AjusteSaldoFondoRotativo::whereBetween(DB::raw('DATE(created_at)'), [$fecha_inicio, $fecha_fin])
            ->where('destinatario_id', $empleado)
            ->where('tipo', $tipo)
            ->get();
    }

    /**
     * Obtiene los registros que pertenecen al mes anterior, pero que por A o B motivo fueron aprobados en el mes que se esta consultando el reporte.
     * @param int|null $empleado_id
     * @param Carbon $fecha_inicio
     * @param Carbon $fecha_fin
     * @param bool $suma si es verdadero se retornan todos los valores que suman, caso contrario retorna los que restan.
     * @return Saldo[]|Builder[]|Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function obtenerRegistrosFueraMes(?int $empleado_id, Carbon $fecha_inicio, Carbon $fecha_fin, bool $suma = true)
    {
        if ($fecha_inicio->day !== 1) return collect();
        $query = Saldo::when(!is_null($empleado_id), function ($q) use ($empleado_id) {
            $q->where('empleado_id', $empleado_id);
        })
            ->where('fecha', '<', $fecha_inicio)
            ->whereBetween('created_at', [$fecha_inicio, $fecha_fin]);
        if ($suma)
            $query->whereIn('tipo_saldo', [Saldo::INGRESO, Saldo::ANULACION]);
        else
            $query->where('tipo_saldo', Saldo::EGRESO);
        return $query->get();
    }

    /**
     * Esta función obtiene los registros que se registraron en el mes consultado pero se aprobaron en un mes posterior.
     * Por tal razón a las `fecha_inicio` y `fecha_fin` dadas se les suma un mes para obtener correctamente dichos registros.
     * @param int|null $empleado_id
     * @param Carbon $fecha_inicio
     * @param Carbon $fecha_fin
     * @return Collection|\Illuminate\Support\Collection
     */
    public function obtenerRegistrosFueraMesFuturo(?int $empleado_id, Carbon $fecha_inicio, Carbon $fecha_fin)
    {
        $registros_fuera_mes_suman = $this->obtenerRegistrosFueraMes($empleado_id, $fecha_inicio->addMonth(), $fecha_fin->addMonth());
        $registros_fuera_mes_restan = $this->obtenerRegistrosFueraMes($empleado_id, $fecha_inicio->addMonth(), $fecha_fin->addMonth(), false);
        return $registros_fuera_mes_suman->merge($registros_fuera_mes_restan);
    }

    /**
     * La función `guardarSaldo` guarda un nuevo registro de saldo para el fondo de un empleado según
     * el tipo de transacción (ingreso o gasto).
     *
     * @param Model $entidad El parámetro `entidad` en la función `guardarSaldo` es una instancia de una
     * clase modelo. Se utiliza para crear un nuevo registro en la relación `saldoFondoRotativo` de
     * este modelo.
     * @param array $data Un array compuesto por lo siguiente:
     * - empleado_id: ID del empleado
     * - tipo: valor que puede ser INGRESO o EGRESO
     * - monto: cantidad a sumar o restar
     * - fecha: la fecha en que se registra el gasto (la que llena el empleado)
     * @throws Throwable
     */
    public static function guardarSaldo(Model $entidad, array $data)
    {
        try {
            DB::beginTransaction();
            $ultimo_registro = Saldo::where('empleado_id', $data['empleado_id'])->orderBy('id', 'desc')->first();
            $ultimo_saldo = $ultimo_registro?->saldo_actual ?? 0;
            $nuevo_saldo = ($data['tipo'] === self::INGRESO) ? $ultimo_saldo + $data['monto'] : $ultimo_saldo - $data['monto'];
            $entidad->saldoFondoRotativo()->create([
                'fecha' => $data['fecha'],
                'saldo_anterior' => $ultimo_saldo,
                'saldo_depositado' => $data['monto'],
                'saldo_actual' => $nuevo_saldo,
                'tipo_saldo' => $data['tipo'],
                'empleado_id' => $data['empleado_id']
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * @throws Throwable
     */
    public static function anularSaldo($entidad, $data)
    {
        try {
            DB::beginTransaction();
            $saldo_anterior = Saldo::where('empleado_id', $data['empleado_id'])->orderBy('id', 'desc')->first();
            $total_saldo_actual = $saldo_anterior !== null ? $saldo_anterior->saldo_actual : 0;
            $nuevo_saldo = ($data['tipo'] === self::INGRESO) ?
                (array('monto' => ($total_saldo_actual + $data['monto']), 'tipo_saldo' => self::ANULACION)) : (array('monto' => ($total_saldo_actual - $data['monto']), 'tipo_saldo' => self::ANULACION));
            $entidad->saldoFondoRotativo()->create([
                'fecha' => $data['fecha'],
                'saldo_anterior' => $total_saldo_actual,
                'saldo_depositado' => $data['monto'],
                'saldo_actual' => $nuevo_saldo['monto'],
                'tipo_saldo' => $nuevo_saldo['tipo_saldo'],
                'empleado_id' => $data['empleado_id']
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * @throws Throwable
     */
    public static function ajustarSaldo($entidad, $data)
    {
        try {
            DB::beginTransaction();
            $empleado_id = $data['destinatario_id'];
            $fecha = Carbon::now();
            $saldo_anterior = Saldo::where('empleado_id', $empleado_id)->orderBy('id', 'desc')->first();
            $total_saldo_actual = $saldo_anterior !== null ? $saldo_anterior->saldo_actual : 0;
            $saldo = [];
            $saldo['fecha'] = $fecha;
            $saldo['saldo_anterior'] = $total_saldo_actual;
            $saldo['saldo_depositado'] = $data['monto'];
            $saldo['saldo_actual'] = $data['tipo'] == AjusteSaldoFondoRotativo::INGRESO ? $total_saldo_actual + $data['monto'] : $total_saldo_actual - $data['monto'];
            // $saldo->fecha_inicio = self::calcular_fechas(date('Y-m-d', strtotime($fecha)))[0];
            // $saldo->fecha_fin = self::calcular_fechas(date('Y-m-d', strtotime($fecha)))[1];;
            $saldo['tipo_saldo'] = $data['tipo'];
            $saldo['empleado_id'] = $empleado_id;

            $entidad->saldoFondoRotativo()->create($saldo);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function EstadoCuentaAnterior($fechaInicio, $id_empleado)
    {
        $mesAnterior = $this->getFechaMesAnterior($fechaInicio);
        return $this->SaldoEstadoCuenta($mesAnterior['inicio'], $mesAnterior['fin'], $id_empleado);
    }

    public static function obtenerSaldoEmpleadoFecha($fecha_anterior, $empleado_id)
    {
        $saldo_grupo = SaldoGrupo::where('id_usuario', $empleado_id)
            ->where('fecha', $fecha_anterior)
            ->first();

        if ($saldo_grupo) {
            return $saldo_grupo;
        }

        return Saldo::where('empleado_id', $empleado_id)
            ->where('fecha', $fecha_anterior)
            ->first();
    }

    public static function obtenerSaldoEmpleadoEntreFechas($fecha_inicio, $fecha_fin, int $empleado_id)
    {
        $saldo_grupo = SaldoGrupo::where('id_usuario', $empleado_id)
            ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
            ->orderBy('id', 'desc')
            ->first();
        if ($saldo_grupo) {
            return $saldo_grupo;
        }

        return Saldo::where('empleado_id', $empleado_id)
            ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
            ->orderBy('id', 'desc')
            ->first();
    }


    public static function obtenerSaldoActualUltimaFecha($fecha, int $empleado_id)
    {
        $saldo_grupo = SaldoGrupo::where('id_usuario', $empleado_id)
            ->where('fecha', '>=', $fecha)
            ->orderBy('id', 'desc')
            ->first();

        if ($saldo_grupo) {
            return SaldoGrupo::where('id_usuario', $empleado_id)
                ->where('fecha', '<=', $fecha)
                ->orderBy('id', 'desc')
                ->first();
        }

        return Saldo::where('empleado_id', $empleado_id)
            ->where('fecha', '<=', $fecha)
            ->orderBy('id', 'desc')
            ->first();
    }


    /**
     * Obtiene el ultimo saldo de un empleado de la tabla fr_saldos, en base a la última fecha de creación del saldo anterior a la `$fecha_inicio`
     * @param int $empleado_id
     * @param $fecha_anterior
     * @param $fecha_inicio
     * @return Saldo|Builder|Model|\Illuminate\Database\Query\Builder|object|null
     */
    public static function obtenerSaldoAnterior(int $empleado_id, $fecha_anterior, $fecha_inicio)
    {
//        Log::channel('testing')->info('Log', ['args de obtenerSaldoAnterior',$empleado_id, $fecha_anterior, $fecha_inicio]);
        if (strtotime($fecha_anterior) < strtotime('05-04-2024')) {
            return SaldoGrupo::where('id_usuario', $empleado_id)
                ->where('fecha', '<=', $fecha_anterior)
                ->orderBy('created_at', 'DESC')
                ->first();
        }
        //        Log::channel('testing')->info('Log', ['saldo fondos', $saldo_fondos]);
        return Saldo::where('empleado_id', $empleado_id)
            ->where('created_at', '<', $fecha_inicio)
            ->orderBy('created_at', 'DESC')
            ->first();
    }

    /**
     * La función verifica si hay registros de saldo para un empleado específico después del 04 de abril 2024.
     * Fecha en que fue la migración de saldos.
     *
     * @param int $empleado_id La función `existeSaldoNuevaTabla` verifica si hay algún registro en la
     * tabla `Saldo` con fecha mayor o igual a '2024-04-04' para un `empleado_id` específico.
     *
     * @return bool Si hay al menos un registro, devuelve "verdadero", de lo contrario devuelve falso.
     */
    public static function existeSaldoNuevaTabla(int $empleado_id)
    {
        $registros_saldos = Saldo::where('fecha', '>=', '2024-04-04')->where('empleado_id', $empleado_id)->get();
        return $registros_saldos->count() > 0;
    }

}
