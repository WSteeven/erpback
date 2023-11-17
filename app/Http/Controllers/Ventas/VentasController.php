<?php

namespace App\Http\Controllers\Ventas;

use App\Exports\Ventas\ReporteValoresCobrarExport;
use App\Exports\Ventas\ReporteVentasExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\VentasRequest;
use App\Http\Resources\Ventas\VentasResource;
use App\Models\ConfiguracionGeneral;
use App\Models\Ventas\Ventas;
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
            $umbral = Ventas::create($datos);
            $modelo = new VentasResource($umbral);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(VentasRequest $request, Ventas $umbral)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $umbral->update($datos);
            $modelo = new VentasResource($umbral->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, Ventas $umbral)
    {
        $umbral->delete();
        return response()->json(compact('umbral'));
    }
    public function generar_reporteCobroJP(Request $request)
    {
        try {
            $fecha_inicio = date('Y-m-d', strtotime($request->fecha_inicio));
            $fecha_fin = date('Y-m-d', strtotime($request->fecha_fin));
            $ventas = Ventas::select(DB::raw('MONTHNAME(fecha_activ) AS mes'), DB::raw('COUNT(*) as total_ventas'))
                ->whereBetween('fecha_activ', [$fecha_inicio, $fecha_fin])
                ->where('pago', true)
                ->groupBy('mes')
                ->orderBy('mes', 'ASC')
                ->get();
            $reporte = [];
            foreach ($ventas as $key => $venta) {
                $total_ventas = $venta->total_ventas;
                $comision =  $total_ventas * 2.5;
                $arpu = $total_ventas * 0.5;
                $metas_altas = $total_ventas > 80 ? $total_ventas * 0.5 : 0;
                $bonotc = 0;
                $bono_trimestral = 0;
                $bono_calidad180 = 0;
                $config = ConfiguracionGeneral::first();
                $reportes[] = [
                    'mes' =>  Utils::$meses[$venta->mes],
                    'comision' => $comision,
                    'total_ventas' => $total_ventas,
                    'arpu' => $arpu,
                    'metas_altas' => $metas_altas,
                    'bonotc' => $bonotc,
                    'bono_trimestral' => $bono_trimestral,
                    'bono_calidad180' => $bono_calidad180
                ];
            }
            $nombre_reporte = 'reporte_valores_cobrar';
            $export_excel = new ReporteValoresCobrarExport(compact('reportes', 'config'));
            return Excel::download($export_excel, $nombre_reporte . '.xlsx');
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', [compact('ex')]);
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
            $ventas = Ventas::whereMonth('created_at', $mes)->whereYear('created_at', $anio)->with('vendedor', 'producto')->get();
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
            $fecha_inicio = date('Y-m-d', strtotime($request->fecha_inicio));
            $fecha_fin = date('Y-m-d', strtotime($request->fecha_fin));

            $ventas = Ventas::whereBetween('fecha_activ', [$fecha_inicio, $fecha_fin])->where('vendedor_id',$request->vendedor)->with('vendedor', 'producto')->get();
            $reportes = Ventas::empaquetarVentas($ventas);
            $nombre_reporte = 'reporte_pagos';
            $config = ConfiguracionGeneral::first();
            $export_excel = new ReporteVentasExport(compact('reportes', 'config', 'fechaConvertida'));
            return Excel::download($export_excel, $nombre_reporte . '.xlsx');
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', [compact('ex')]);
        }
    }
}
