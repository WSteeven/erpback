<?php

namespace Src\App\VentasClaro;

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
        $cortes = CortePagoComision::where('estado', '<>', CortePagoComision::ANULADA)->get(['fecha_inicio', 'fecha_fin']);
        $ventas = Venta::all();
        $fechas_ventas = $ventas->pluck('fecha_activacion')->toArray();

        $fecha_inicio = Carbon::parse(min($fechas_ventas));

        $fechasArray = [];

        while ($fecha_inicio->lessThanOrEqualTo(Carbon::now())) {
            $fechasArray[] = $fecha_inicio->format('Y/m/d');//->toDateString();
            $fecha_inicio->addDay();
        }

        $fechasFiltradas = array_filter($fechasArray, function ($fecha) use ($cortes) {
            foreach ($cortes as $corte) {
                if (self::fechaEnRango($fecha, $corte)) return false;
            }
            return true;
        });

        return $fechasFiltradas;
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
     * @return bool un valor booleano que indica si la fecha dada se encuentra dentro del rango
     * especificado.
     */
    private static function fechaEnRango($fecha, $rango)
    {
        $fecha = Carbon::parse($fecha);
        return $fecha->between(Carbon::parse($rango['fecha_inicio']), Carbon::parse($rango['fecha_fin']), true);
    }
}
