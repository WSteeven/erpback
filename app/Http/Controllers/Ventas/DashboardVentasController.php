<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Resources\Ventas\VentasResource;
use App\Models\Ventas\ProductoVentas;
use App\Models\Ventas\Ventas;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardVentasController extends Controller
{
    public function index()
    {
        try {
        // Obtencion de parametros
        $idVendedor = request('vendedor_id');
        $fechaInicio = request('fecha_inicio');
        $fechaFin = request('fecha_fin');
        // Conversion de fechas
        $fecha_inicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fecha_fin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();
        $queryVentas =  Ventas::whereBetween('created_at', [$fecha_inicio, $fecha_fin])->where('vendedor_id', $idVendedor)->with('vendedor', 'producto');
        $cantidad_ventas = $queryVentas->where('vendedor_id', $idVendedor)->get()->count();
        $cantidad_ventas_instaladas =  Ventas::whereBetween('created_at', [$fecha_inicio, $fecha_fin])->where('vendedor_id', $idVendedor)->where('estado_activ', 'APROBADO')->get()->count();
        $cantidad_ventas_por_instalar =  Ventas::whereBetween('created_at', [$fecha_inicio, $fecha_fin])->where('vendedor_id', $idVendedor)->where('estado_activ', 'PENDIENTE')->get()->count();
        $cantidad_ventas_por_rechazadas =  Ventas::whereBetween('created_at', [$fecha_inicio, $fecha_fin])->where('vendedor_id', $idVendedor)->where('estado_activ', 'RECHAZADA')->get()->count();
        $ventasPorEstado = $queryVentas->get();
        $ventasPorPlanes = $queryVentas->where('estado_activ', 'APROBADO')->get();
        $ventasPorEstado = VentasResource::collection($ventasPorEstado);
        $graficoVentasPorEstado = Ventas::select('estado_activ', DB::raw('COUNT(*) as total_ventas'))
            ->groupBy('estado_activ')
            ->get();
        $graficoVentasPorPlanes = [];
        foreach ($ventasPorPlanes as $venta){
            $planId = optional($venta->producto->plan)->nombre;
            if (!isset($graficoVentasPorPlanes[$planId])) {
                $graficoVentasPorPlanes[$planId] = 0;
            }
            $graficoVentasPorPlanes[$planId]++;

        }
        $ventasPorPlanes = VentasResource::collection($ventasPorPlanes);
        // Generar el JSON
        $ventasPorEstadoBar = [
            'labels' => $graficoVentasPorEstado->pluck('estado_activ'),
            'datasets' => [
                [
                    'backgroundColor' => [
                        "#83B11E",
                        "#D62C18",
                        "#D4CB13"
                    ],
                    'label' => "Cantidad de ventas por estado",
                    'data' => $graficoVentasPorEstado->pluck('total_ventas'),
                ],
            ],
        ];

        $ventasPorPlanesoBar = [
            'labels' => array_keys($graficoVentasPorPlanes),
            'datasets' => [
                [
                    'backgroundColor' => [
                        "#D62C18 ",
                        "#DB6C25",
                        "#25DB8A"
                    ],
                    'label' => "Cantidad de ventas por planes",
                    'data' =>array_values($graficoVentasPorPlanes),
                ],
            ],
        ];

        $results = compact('cantidad_ventas', 'cantidad_ventas_instaladas', 'cantidad_ventas_por_instalar', 'cantidad_ventas_por_rechazadas', 'ventasPorEstado', 'ventasPorPlanes', 'ventasPorEstadoBar','ventasPorPlanesoBar');
        return response()->json(compact('results'));

    } catch (Exception $e) {
        Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
    }

    }
}
