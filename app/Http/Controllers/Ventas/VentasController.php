<?php

namespace App\Http\Controllers\Ventas;

use App\Exports\Ventas\ReportePagoExport;
use App\Exports\Ventas\ReporteValoresCobrarExport;
use App\Exports\Ventas\ReporteVentasExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\VentasRequest;
use App\Http\Resources\Ventas\VentasResource;
use App\Models\ConfiguracionGeneral;
use App\Models\Ventas\BonoMensualCumplimiento;
use App\Models\Ventas\BonoTrimestralCumplimiento;
use App\Models\Ventas\Chargebacks;
use App\Models\Ventas\Comisiones;
use App\Models\Ventas\PagoComision;
use App\Models\Ventas\Ventas;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;
use Maatwebsite\Excel\Facades\Excel;

class VentasController extends Controller
{
    private $entidad = 'Ventas';
    public function __construct()
    {
        $this->middleware('can:puede.ver.ventas')->only('index', 'show');
        $this->middleware('can:puede.crear.ventas')->only('store');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = Ventas::ignoreRequest(['campos'])->filter()->get();
        $results = VentasResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, Ventas $venta)
    {
        $modelo = new VentasResource($venta);
        return response()->json(compact('modelo'));
    }

    public function store(VentasRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $venta = Ventas::create($datos);
            $modelo = new VentasResource($venta);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(VentasRequest $request, Ventas $venta)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $venta->update($datos);
            $modelo = new VentasResource($venta->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, Ventas $venta)
    {
        $venta->delete();
        return response()->json(compact('venta'));
    }

    public function generar_reporteCobroJP(Request $request)
    {
        try {
            DB::beginTransaction();
            $fecha_inicio = date('Y-m-d', strtotime($request->fecha_inicio));
            $fecha_fin = date('Y-m-d', strtotime($request->fecha_fin));
            $ventas = Ventas::select(
                DB::raw('MONTHNAME(fecha_activacion) AS mes'),
                DB::raw('COUNT(*) as cantidad_ventas'),
                DB::raw('SUM(ventas_producto_ventas.precio) as total_ventas'),
                DB::raw('AVG(ventas_producto_ventas.precio) as promedio_precio')
            )
                ->join('ventas_producto_ventas', 'ventas_producto_ventas.id', '=', 'ventas_ventas.producto_id')
                ->whereBetween('fecha_activacion', [$fecha_inicio, $fecha_fin])
                ->where('pago', true)
                ->groupBy('mes')
                ->orderBy('mes', 'ASC')
                ->get();
            $ventas_tc = Ventas::select(
                DB::raw('MONTHNAME(fecha_activacion) AS mes'),
                DB::raw('COUNT(*) as cantidad_ventas'),
                DB::raw('SUM(ventas_producto_ventas.precio) as total_ventas'),
                DB::raw('AVG(ventas_producto_ventas.precio) as promedio_precio')
            )
                ->join('ventas_producto_ventas', 'ventas_producto_ventas.id', '=', 'ventas_ventas.producto_id')
                ->whereBetween('fecha_activacion', [$fecha_inicio, $fecha_fin])
                ->where('pago', true)
                ->where('forma_pago', 'TC')
                ->groupBy('mes')
                ->orderBy('mes', 'ASC')
                ->get();
            $reportes = $this->generarReporte($ventas, $ventas_tc);
            $nombre_reporte = 'reporte_valores_cobrar';
            $config = ConfiguracionGeneral::first();
            $export_excel = new ReporteValoresCobrarExport(compact('reportes', 'config'));
            DB::commit();
            return Excel::download($export_excel, $nombre_reporte . '.xlsx');
        } catch (Exception $ex) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al generar reporte de cobro de JP' . $ex->getMessage() . ' ' . $ex->getLine()], 422);
        }
    }


    private function generarReporte($ventas, $ventas_tc)
    {
        try {
            DB::beginTransaction();
            $reporte = [];
            for ($i = 0; $i < count($ventas); $i++) {
                $mes = $ventas[$i]->mes;
                $total_ventas = $ventas[$i]->total_ventas;
                $arreglo_ventas_tc =  $this->buscarArreglo($ventas_tc, $mes);
                $total_ventas_tc = $arreglo_ventas_tc != null ? $arreglo_ventas_tc->total_ventas : 0;
                $promedio_ventas = $ventas[$i]->promedio_precio;
                $cantidad_ventas = $ventas[$i]->cantidad_ventas;
                $comision = $total_ventas * $this->calcularTarifaBasica('comision');
                $arpu = $total_ventas * $this->calcularTarifaBasica('bcarpu');
                $metas_altas = $total_ventas > 80 ? $total_ventas * $this->calcularTarifaBasica('metas_altas') : 0;
                $bonotc = $total_ventas_tc * $this->calcularTarifaBasica('tc');
                $bono_trimestral = 0;
                $bono_calidad180 = 0;
                $reporte[] = [
                    'mes' => $mes,
                    'comision' => $comision,
                    'total_ventas' => $total_ventas,
                    'arpu' => $arpu,
                    'metas_altas' => $metas_altas,
                    'bonotc' => $bonotc,
                    'bono_trimestral' => $bono_trimestral,
                    'bono_calidad180' => $bono_calidad180
                ];
            }
            DB::commit();
            return $reporte;
        } catch (Exception $ex) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al generar reporte' . $ex->getMessage() . ' ' . $ex->getLine()], 422);
        }
    }
    public function buscarArreglo($arreglo, $mes)
    {
        try {
            DB::beginTransaction();
            $valor = null;
            if (count($arreglo) > 0) {
                $indice = array_search($mes, array_column($arreglo->toArray(), "mes"));
                if ($indice !== false) {
                    $valor = $arreglo[$indice];
                }
            }
            DB::commit();
            return $valor;
        } catch (Exception $ex) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al generar reporte' . $ex->getMessage() . ' ' . $ex->getLine()], 422);
        }
    }
    public function calcularTarifaBasica($etiqueta)
    {
        try {
            DB::beginTransaction();
            $tarifas_basicas = [];
            $id_esquemas_comision = [1, 2, 3, 4, 5, 6];
            foreach ($id_esquemas_comision as $id_esquema_comision) {
                $esquema_comision = EsquemaComisionController::obtener_esquema_comision($id_esquema_comision);

                switch ($id_esquema_comision) {
                    case 1:
                        $tarifas_basicas['comision'] = $esquema_comision->tarifa_basica;
                        break;
                    case 2:
                        $tarifas_basicas['bcarpu'] = $esquema_comision->tarifa_basica;
                        break;
                    case 3:
                        $tarifas_basicas['metas_altas'] = $esquema_comision->tarifa_basica;
                        break;
                    case 4:
                        $tarifas_basicas['tc'] = $esquema_comision->tarifa_basica;
                        break;
                    case 5:
                        $tarifas_basicas['90_dias'] = $esquema_comision->tarifa_basica;
                        break;
                    case 6:
                        $tarifas_basicas['180_dias'] = $esquema_comision->tarifa_basica;
                        break;
                }
            }
            return $tarifas_basicas[$etiqueta];
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al generar reporte' . $ex->getMessage() . ' ' . $ex->getLine()], 422);
        }
    }
    public function reporte_ventas(Request $request)
    {
        try {
            list($mes, $anio) = explode('-', $request->mes);
            // Convertir el mes a su nombre
            $meses = ["ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"];
            $nombreMes = $meses[$mes - 1];
            // Concatenar los componentes de la fecha
            $fechaConvertida = "$nombreMes DEL $anio";
            $ventas = Ventas::whereMonth('fecha_activacion', $mes)->whereYear('fecha_activacion', $anio)->with('vendedor', 'producto','cliente')->get();
            Log::channel('testing')->info('Log', [compact('ventas')]);
            $reportes = Ventas::empaquetarVentas($ventas);
            $nombre_reporte = 'reporte_valores_cobrar';
            $config = ConfiguracionGeneral::first();
            $export_excel = new ReporteVentasExport(compact('reportes', 'config', 'fechaConvertida'));
            return Excel::download($export_excel, $nombre_reporte . '.xlsx');
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', [compact('ex')]);
        }
    }
    public function reporte_pagos(Request $request)
    {
        try {
            DB::beginTransaction();
            $fecha_inicio = date('Y-m-d', strtotime($request->fecha_inicio));
            $fecha_fin = date('Y-m-d', strtotime($request->fecha_fin));
            $mes = date("Y-m", strtotime($fecha_fin));
            $bonosMensuales = BonoMensualCumplimiento::select(DB::raw('Concat(MONTHNAME(created_at),"-",Year(created_at)) AS mes'), DB::raw('SUM(valor) AS total_bmc'))
                ->where('vendedor_id', $request->vendedor)
                ->where('mes',$mes)
                ->groupBy('mes')
                ->get();
            $bonosTrimestrales = BonoTrimestralCumplimiento::select(DB::raw('Concat(MONTHNAME(created_at),"-",Year(created_at)) AS mes'), DB::raw('SUM(valor) AS total_btc'))
                ->where('vendedor_id', $request->vendedor)
                ->whereBetween(DB::raw('STR_TO_DATE(created_at, "%Y-%m-%d")'), ['2023-11-01', '2023-11-17'])
                ->groupBy('mes')
                ->get();
            $comisiones = PagoComision::select(DB::raw('Concat(MONTHNAME(fecha_inicio),"-",Year(fecha_inicio)) AS mes'), DB::raw('SUM(valor) AS total_comisiones'))
                ->where('fecha_inicio', $fecha_inicio)
                ->where('fecha_fin',  $fecha_fin)
                ->where('vendedor_id', $request->vendedor)
                ->groupBy('mes')
                ->get();
            $chargebacks = Chargebacks::select(DB::raw('Concat(MONTHNAME(fecha),"-",Year(fecha)) AS mes'), DB::raw('SUM(valor) AS total_chargeback'))
                ->join('ventas_ventas', 'ventas_chargebacks.venta_id', '=', 'ventas_ventas.id')
                ->where('ventas_ventas.vendedor_id', $request->vendedor)
                ->whereBetween('ventas_chargebacks.fecha', [$fecha_inicio, $fecha_fin])
                ->groupBy('mes')
                ->get();
            $data = compact('bonosMensuales', 'bonosTrimestrales', 'comisiones', 'chargebacks');
            $reportes = [];
            $bmc = 0;
            $btc = 0;
            $totalComisiones = 0;
            $totalChargeback =  0;
            foreach ($data as $itemType => $itemValues) {
                foreach ($itemValues as $monthData) {
                    $month = $monthData['mes'];
                    $mes = explode('-', $month);
                    $month = Utils::$meses[$mes[0]] . '-' . $mes[1];
                    $bmc += $monthData['total_bmc']  ?? 0;
                    $btc += $monthData['total_btc']  ?? 0;
                    $totalComisiones += $monthData['total_comisiones']  ?? 0;
                    $totalChargeback += $monthData['total_chargeback'] ?? 0;
                    $ingresos = $bmc + $btc + $totalComisiones;
                    $egresos =  $totalChargeback;
                    $total = $ingresos - $egresos;
                    $reportes[$month] = [
                        'bmc' => $bmc,
                        'btc' => $btc,
                        'total_comisiones' => $totalComisiones,
                        'chargebacks' => $totalChargeback,
                        'ingresos' => $ingresos,
                        'egresos' => $egresos,
                        'total_a_pagar' => $total
                    ];
                }
            }


            $nombre_reporte = 'reporte_pagos';
            $config = ConfiguracionGeneral::first();
            $export_excel = new ReportePagoExport(compact('reportes', 'config'));
            DB::commit();
            return Excel::download($export_excel, $nombre_reporte . '.xlsx');
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ["error en pago de comisiones", $e->getmessage(), $e->getLine()]);
        }
    }
}
