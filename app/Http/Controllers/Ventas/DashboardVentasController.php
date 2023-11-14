<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Resources\Ventas\VentasResource;
use App\Models\Ventas\ProductoVentas;
use App\Models\Ventas\Ventas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardVentasController extends Controller
{
    public function index()
    {
        // Obtencion de parametros
        $idVendedor = request('vendedor_id');
        $fechaInicio = request('fecha_inicio');
        $fechaFin = request('fecha_fin');
        // Conversion de fechas
        $fecha_inicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fecha_fin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();
        $query_ventas =  Ventas::whereBetween('created_at', [$fecha_inicio, $fecha_fin])->where('vendedor_id', $idVendedor)->with('vendedor', 'producto');
        $cantidad_ventas = $query_ventas->where('vendedor_id', $idVendedor)->get()->count();
        $cantidad_ventas_instaladas =  Ventas::whereBetween('created_at', [$fecha_inicio, $fecha_fin])->where('vendedor_id', $idVendedor)->where('estado_activ', 'APROBADO')->get()->count();
        $cantidad_ventas_por_instalar =  Ventas::whereBetween('created_at', [$fecha_inicio, $fecha_fin])->where('vendedor_id', $idVendedor)->where('estado_activ', 'PENDIENTE')->get()->count();
        $cantidad_ventas_por_rechazadas =  Ventas::whereBetween('created_at', [$fecha_inicio, $fecha_fin])->where('vendedor_id', $idVendedor)->where('estado_activ', 'RECHAZADA')->get()->count();
        $ventasPorEstado = $query_ventas->get();
        $ventasPorPlanes = $query_ventas->where('estado_activ', 'APROBADO')->get();
        $ventasPorEstado = VentasResource::collection($ventasPorEstado);
        $graficoVentasPorEstado = Ventas::select('estado_activ', DB::raw('COUNT(*) as total_ventas'))
            ->groupBy('estado_activ')
            ->get();
        $graficoVentasPorPlanes = ProductoVentas::select('ventas_planes.nombre', DB::raw('COUNT(*) AS total_ventas'))
            ->join('ventas_planes', 'ventas_producto_ventas.plan_id', '=', 'ventas_planes.id')
            ->groupBy('ventas_planes.nombre')
            ->get();

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
            'labels' => $graficoVentasPorPlanes->pluck('nombre'),
            'datasets' => [
                [
                    'backgroundColor' => [
                        "#D62C18 ",
                        "#DB6C25",
                        "#25DB8A"
                    ],
                    'label' => "Cantidad de ventas por planes",
                    'data' => $graficoVentasPorPlanes->pluck('total_ventas'),
                ],
            ],
        ];

        $results = compact('cantidad_ventas', 'cantidad_ventas_instaladas', 'cantidad_ventas_por_instalar', 'cantidad_ventas_por_rechazadas', 'ventasPorEstado', 'ventasPorPlanes', 'ventasPorEstadoBar','ventasPorPlanesoBar');
        return response()->json(compact('results'));
    }
}
