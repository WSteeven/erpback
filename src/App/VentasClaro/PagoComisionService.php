<?php

namespace Src\App\VentasClaro;

use App\Http\Resources\Ventas\CortePagoComisionResource;
use App\Http\Resources\Ventas\VentaResource;
use App\Models\Ventas\Chargeback;
use App\Models\Ventas\Comision;
use App\Models\Ventas\CortePagoComision;
use App\Models\Ventas\Modalidad;
use App\Models\Ventas\PagoComision;
use App\Models\Ventas\ProductoVenta;
use App\Models\Ventas\Vendedor;
use App\Models\Ventas\Venta;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PagoComisionService
{
    public function __construct()
    {
    }

    private static function tabla_comisiones($fecha_inicio, $fecha_fin)
    {
        try {
            DB::beginTransaction();
            $vendedor = Vendedor::get();
            foreach ($vendedor as $vendedor) {
                $chargeback = Chargeback::select(DB::raw('SUM(valor) AS total_chargeback'))
                    ->join('ventas_ventas', 'ventas_chargebacks.venta_id', '=', 'ventas_ventas.id')
                    ->where('ventas_ventas.vendedor_id', $vendedor->id)
                    ->whereBetween('ventas_chargebacks.fecha', [$fecha_inicio, $fecha_fin])
                    ->get();
                $comisiones = self::calcularComisiones($vendedor->empleado_id, $fecha_inicio, $fecha_fin);
                PagoComision::create([
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_fin' => $fecha_fin,
                    'chargeback' => $chargeback ? $chargeback[0]['total_chargeback'] : 0,
                    'vendedor_id' => $vendedor->id,
                    'valor' =>  $comisiones,
                ]);
            }
            self::pagar_comisiones($vendedor->modalidad_id, $vendedor->id, $fecha_inicio, $fecha_fin);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ["error en calcular_ventas_comision", $e->getmessage(), $e->getLine()]);
            throw $e;
        }
    }

    private static function calcularComisiones($empleado_id, $fecha_inicio, $fecha_fin)
    {
        try {
            DB::beginTransaction();
            $vendedor = Vendedor::find($empleado_id);
            $comisiones = null;
            $ventas = null;
            $pago_comision = self::calcularVentasConComision($empleado_id, $fecha_inicio, $fecha_fin, $vendedor);
            $modalidad = Modalidad::where('id', $vendedor->modalidad_id)->first();
            $limite_venta =  $modalidad != null ? $modalidad->umbral_minimo : 0;

            if ($vendedor->tipo_vendedor == 'VENDEDOR') {
                $ventas = $pago_comision['ventas']->orderBy('id')->skip($limite_venta)->take(999999)->get();
                $comisiones = array_reduce($ventas->toArray(), function ($carry, $item) {
                    return $carry + $item["comision_vendedor"];
                }, 0);
            } else {
                $ventas = $pago_comision['ventas']->orderBy('ventas_ventas.id')->get();
                $comisiones = array_reduce($ventas->toArray(), function ($carry, $item) use ($vendedor) {
                    $plan = ProductoVenta::select('plan_id')->where('id', $item['producto_id'])->first();
                    $plan = $plan != null ? $plan->plan_id : 0;
                    $forma_pago = $item['forma_pago'];
                    $tipo_vendedor = $vendedor->tipo_vendedor;
                    $comision_pagar = Comision::where('forma_pago', $forma_pago)->where('plan_id', $plan)->where('tipo_vendedor', $tipo_vendedor)->first();
                    $comision_pagar =  $comision_pagar != null ? $comision_pagar->comision : 0;
                    return $carry + $comision_pagar;
                }, 0);
            }
            //  $this->pagar_comisiones($vendedor->modalidad_id, $vendedor->id, $fecha_inicio, $fecha_fin);
            DB::commit();
            return $comisiones;
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ["error en calcular_comision", $e->getmessage(), $e->getLine()]);
            throw $e;
            // throw new Exception($e->getMessage() . '. ' . $e->getLine());
        }
    }

    public function calcularVentasConComision($empleado_id, $fecha_inicio, $fecha_fin, Vendedor $vendedor)
    {
        try {
            DB::beginTransaction();
            $ventas = Venta::whereBetween('fecha_activacion', [$fecha_inicio, $fecha_fin]);
            $comisiones = PagoComision::where('fecha_inicio', $fecha_inicio)->where('fecha_fin', $fecha_fin);
            $pago_comision = null;
            if ($vendedor->tipo_vendedor == Vendedor::VENDEDOR) {
                $pago_comision = $comisiones->where('vendedor_id',  $vendedor->id)->get()->count();
                $ventas->with('producto')->where('vendedor_id', $vendedor->id);
            } else {
                $pago_comision = PagoComision::join('ventas_vendedores', 'ventas_pagos_comisiones.vendedor_id', '=', 'ventas_vendedores.empleado_id')
                    ->where('ventas_vendedores.jefe_inmediato_id', $empleado_id)
                    ->where('fecha_inicio', $fecha_inicio)
                    ->where('fecha_fin', $fecha_fin)
                    ->get()
                    ->count();
                $ventas = Venta::join('ventas_vendedores', 'ventas_ventas.vendedor_id', '=', 'ventas_vendedores.empleado_id')
                    ->where('ventas_vendedores.jefe_inmediato_id', $empleado_id)
                    ->whereBetween('fecha_activacion', [$fecha_inicio, $fecha_fin]);
            }
            DB::commit();
            return compact('pago_comision', 'ventas');
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ["error en calcular_ventas_comision linea 183", $e->getmessage(), $e->getLine()]);
            throw $e;
        }
    }

    private static function pagar_comisiones($modalidad, $vendedor_id, $fecha_inicio, $fecha_fin)
    {
        try {
            DB::beginTransaction();
            $pago_comision = PagoComision::where('vendedor_id', $vendedor_id)->get()->count();
            $limite_venta = 0;
            $query_ventas = Venta::whereBetween('fecha_activacion', [$fecha_inicio, $fecha_fin])
                ->where('estado_activacion', 'APROBADO')
                ->where('vendedor_id', $vendedor_id);
            $comisiones = null;
            if ($pago_comision == 0) {
                $modalidad = Modalidad::where('id', $modalidad)->first();
                $limite_venta =  $modalidad != null ? $modalidad->umbral_minimo : 0;
                $comisiones = $query_ventas->orderBy('id')->skip($limite_venta)->take(999999)->get();
            } else {
                $comisiones = $query_ventas->get();
            }
            $ids = $comisiones->pluck('id');
            Venta::whereIn('id', $ids)->update([
                'pago' => true,
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ["error en pago de comisiones 142", $e->getmessage(), $e->getLine()]);
            throw $e;
        }
    }

    /**
     * La función "fechasDisponiblesCorte" devuelve un conjunto de fechas disponibles para un corte de
     * pago basado en las fechas de activaciones de ventas y cortes de pago existentes.
     * 
     * @return array una variedad de fechas disponibles para un corte de pago.
     */
    public static function fechasDisponiblesCorte()
    {
        try {
            $cortes = CortePagoComision::where('estado', '<>', CortePagoComision::ANULADA)->get(['fecha_inicio', 'fecha_fin']);
            $ventas = Venta::whereNotNull('fecha_activacion')->get('fecha_activacion');

            //se hace un array con todos los campos fecha_activacion
            $fechas_ventas = $ventas->pluck('fecha_activacion')->toArray();

            //se toma la fecha mas pequeña para completar el array de fechas disponibles hasta la actual
            $fecha_inicio = Carbon::parse(min($fechas_ventas));

            $fechasArray = [];

            // se crea un array de fechas desde la inicial hasta la actual
            while ($fecha_inicio->lessThanOrEqualTo(Carbon::now())) {
                $fechasArray[] = $fecha_inicio->format('Y/m/d'); //->toDateString();
                $fecha_inicio->addDay();
            }

            // se filtran los rangos de fechas de cortes para que no esten disponibles para seleccionar en el front
            $fechasFiltradas = array_filter($fechasArray, function ($fecha) use ($cortes) {
                foreach ($cortes as $corte) {
                    if (self::fechaEnRango($fecha, $corte)) return false;
                }
                return true;
            });

            return $fechasFiltradas;
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ["error fechasDisponiblesCorte", $th->getLine(), $th->getMessage()]);
            throw $th;
        }
    }

    /**
     * La función `fechaEnRango` verifica si una fecha determinada está dentro de un rango específico.
     * 
     * @param string $fecha El parámetro fecha es una fecha que debe verificarse si se encuentra dentro de un
     * rango específico.
     * @param array $rango Se espera que el parámetro `rango` sea un arreglo asociativo con dos claves:
     * `fecha_inicio` y `fecha_fin`. Estas claves representan las fechas de inicio y finalización de un
     * rango.
     * 
     * @return Boolean un valor booleano que indica si la fecha dada se encuentra dentro del rango
     * especificado.
     */
    private static function fechaEnRango($fecha, $rango)
    {
        try {
            $fecha = Carbon::parse($fecha);
            return $fecha->between(Carbon::parse($rango['fecha_inicio']), Carbon::parse($rango['fecha_fin']), true);
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ["error fechaEnRango", $th->getLine(), $th->getMessage()]);
            throw $th;
        }
    }

    public static function empaquetarDatosCortePagoComision(CortePagoComision $corte)
    {
        $results = [];
        $empleados = [];
        $modelo = new CortePagoComisionResource($corte);
        $results = $modelo->resolve();
        foreach ($corte->detalles()->get() as $i => $detalle) {
            $row['fecha_inicio'] = $detalle->fecha_inicio;
            $row['fecha_fin'] = $detalle->fecha_fin;
            $row['corte_info'] = $detalle->corte->nombre;
            $row['vendedor_info'] = $detalle->vendedor->empleado->nombres . ' ' . $detalle->vendedor->empleado->apellidos;
            $row['chargeback'] = $detalle->chargeback;
            $row['ventas'] = $detalle->ventas;
            $row['valor'] = $detalle->valor;
            $row['pagado'] = $detalle->pagado;
            $empleados[$i] = $row;
        }
        $results['listadoEmpleados'] = $empleados;

        return $results;
    }
    public static function empaquetarVentasCortePagoComision(CortePagoComision $corte)
    {
        try {
            $results = [];
            $count = 0;
            $ventas = Venta::where(function ($query) use ($corte) {
                $query->whereBetween('fecha_activacion', [date('Y-m-d', strtotime($corte->fecha_inicio)), date('Y-m-d', strtotime($corte->fecha_fin))])
                    ->orWhereBetween('fecha_pago_primer_mes', [date('Y-m-d', strtotime($corte->fecha_inicio)), date('Y-m-d', strtotime($corte->fecha_fin))]);
            })->where('estado_activacion', Venta::ACTIVADO)->get();
            // Log::channel('testing')->info('Log', ['ventas', $ventas]);
            $ventas_vendedores_ids = $ventas->unique('vendedor_id')->pluck('vendedor_id');
            foreach ($ventas_vendedores_ids as $v) {
                $vendedor = Vendedor::find($v);
                [$ventas_con_comision, $ventas_sin_comision] = Vendedor::obtenerVentasConComision($vendedor, $corte->fecha_inicio, $corte->fecha_fin);
                if (!is_null($ventas_sin_comision))
                    foreach ($ventas_sin_comision as $index => $venta) {
                        $row['vendedor'] =  $venta->vendedor->empleado->apellidos . ' ' . $venta->vendedor->empleado->nombres;
                        $row['tipo_vendedor'] =  $venta->vendedor->modalidad->nombre;
                        $row['ciudad'] = $venta->vendedor->empleado->canton->canton;
                        $row['codigo_orden'] =  $venta->orden_id;
                        $row['identificacion'] =  $venta->vendedor->empleado->identificacion;
                        $row['identificacion_cliente'] = $venta->cliente != null ? $venta->cliente->identificacion : '';
                        $row['cliente'] =  $venta->cliente != null ? $venta->cliente->nombres . ' ' . $venta->cliente->apellidos : '';
                        $row['venta'] = 1;
                        $row['fecha_ingreso'] = $venta->created_at;
                        $row['fecha_activacion'] =  $venta->fecha_activacion;
                        $row['plan'] = $venta->producto->plan->nombre;
                        $row['precio'] =  number_format($venta->producto->precio, 2, ',', '.');
                        $row['forma_pago'] = $venta->forma_pago;
                        $row['orden_interna'] = $venta->orden_interna;
                        $row['comisiona'] = false;
                        $results[$count] = $row;
                        $count++;
                    }
                foreach ($ventas_con_comision as $index => $venta) {
                    $row['vendedor'] =  $venta->vendedor->empleado->apellidos . ' ' . $venta->vendedor->empleado->nombres;
                    $row['tipo_vendedor'] =  $venta->vendedor->modalidad->nombre;
                    $row['ciudad'] = $venta->vendedor->empleado->canton->canton;
                    $row['codigo_orden'] =  $venta->orden_id;
                    $row['identificacion'] =  $venta->vendedor->empleado->identificacion;
                    $row['identificacion_cliente'] = $venta->cliente != null ? $venta->cliente->identificacion : '';
                    $row['cliente'] =  $venta->cliente != null ? $venta->cliente->nombres . ' ' . $venta->cliente->apellidos : '';
                    $row['venta'] = 1;
                    $row['fecha_ingreso'] = $venta->created_at;
                    $row['fecha_activacion'] =  $venta->fecha_activacion;
                    $row['plan'] = $venta->producto->plan->nombre;
                    $row['precio'] =  number_format($venta->producto->precio, 2, ',', '.');
                    $row['forma_pago'] = $venta->forma_pago;
                    $row['orden_interna'] = $venta->orden_interna;
                    $row['comisiona'] = true;
                    $results[$count] = $row;
                    $count++;
                }
                // Log::channel('testing')->info('Log', ['ventas empaquetadas, resultado', $results]);
            }
            return $results;
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['error en empaquetarVentasCortePagoComision', $th->getLine(), $th->getMessage()]);
            throw $th;
        }
    }
    public static function actualizarEstadoDetallesCortePagoComision(CortePagoComision $corte)
    {
        try {
            foreach ($corte->detalles()->get() as $detalle) {
                $detalle->pagado = true;
                $detalle->save();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
