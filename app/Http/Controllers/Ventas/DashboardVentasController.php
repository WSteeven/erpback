<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Resources\Ventas\VentaResource;
use App\Models\Ventas\Venta;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class DashboardVentasController extends Controller
{
    public function index(Request $request)
    {
        try {
            Log::channel('testing')->info('Log', ['request recibida', request()->all()]);
            $fecha_inicio = date('Y-m-d', strtotime($request->fecha_inicio));
            $fecha_fin = date('Y-m-d', strtotime($request->fecha_fin));
            $ventas = Venta::whereBetween('fecha_activacion', [$fecha_inicio, $fecha_fin])
                ->when($request->vendedor && $request->tipo == 'empleado', function ($query) use ($request) {
                    $query->where('vendedor_id', $request->vendedor);
                })
                ->get();
            switch ($request->tipo) {
                case 'general':
                    $results = $this->obtenerDashboardReporteGeneral($ventas);
                    break;
                default:
                    $results = $this->obtenerDashboardReporteEmpleado($ventas, $request->vendedor);
            }

            return response()->json(compact('results'));
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
            throw ValidationException::withMessages(['error' => [$e->getMessage() . '. ' . $e->getLine()]]);
        }
    }


    private function obtenerDashboardReporteGeneral($ventas)
    {
        $cantidad_ventas = $ventas->count();
        $cantidad_ventas_instaladas = $ventas->where('estado_activacion', Venta::ACTIVADO)->count();
        $cantidad_ventas_por_instalar = $ventas->where('estado_activacion', Venta::APROBADO)->count();
        Log::channel('testing')->info('Log', ['ventas', $ventas]);

        return compact(
            'cantidad_ventas',
            'cantidad_ventas_instaladas',
            'cantidad_ventas_por_instalar',
            'cantidad_ventas_por_rechazadas',
            'ventasPorEstado',
            'ventasPorPlanes',
            'ventasPorEstadoBar',
            'ventasPorPlanesoBar',
            'ventasTiemposLine',
            'ventasPorMes'
        );
    }
    private function obtenerDashboardReporteEmpleado($ventas, $vendedor)
    {
        $cantidad_ventas = $ventas->count();
        $cantidad_ventas_instaladas = $ventas->where('estado_activacion', Venta::ACTIVADO)->count();
        $cantidad_ventas_por_instalar =  $ventas->where('estado_activacion', Venta::APROBADO)->count();
        $cant_ventas_por_debito_bancario = $ventas->where('forma_pago', Venta::D_BANCARIO)->count();
        $cant_ventas_por_efectivo = $ventas->where('forma_pago', Venta::EFECTIVO)->count();
        $cant_ventas_por_tc = $ventas->where('forma_pago', Venta::TC)->count();
        $ventasPorEstado = $ventas;
        $ventasPorPlanes = $ventas->where('estado_activacion', 'APROBADO');
        Log::channel('testing')->info('Log', ['ventas_por_planes:', $ventasPorPlanes]);
        $ventasPorEstado = VentaResource::collection($ventasPorEstado);
        $graficoVentasPorEstado = Venta::select('estado_activacion', DB::raw('COUNT(*) as total_ventas'))
            ->where('vendedor_id', $vendedor)
            // ->whereBetween('created_at', [$fecha_inicio, $fecha_fin])
            ->groupBy('estado_activacion')
            ->get();
        $graficoVentasPorPlanes = [];
        foreach ($ventasPorPlanes as $venta) {
            $planId = optional($venta->producto->plan)->nombre;
            if (!isset($graficoVentasPorPlanes[$planId])) {
                $graficoVentasPorPlanes[$planId] = 0;
            }
            $graficoVentasPorPlanes[$planId]++;
        }
        $ventas_mes = Venta::select(DB::raw('Concat(MONTHNAME(created_at),"-",Year(created_at)) AS mes'), DB::raw('COUNT(*) as total_ventas'))
            ->where('created_at', '<=', Carbon::now())
            ->where('estado_activacion', 'APROBADO')
            ->groupBy('mes')
            ->orderBy('mes', 'desc')
            ->get();
        $ventas_mes = $ventas_mes->map(function ($venta) {
            $mes = explode('-', $venta->mes);
            $venta->mes = Utils::$meses[$mes[0]] . '-' . $mes[1];
            return $venta;
        });
        $ventasPorMes = Venta::where('created_at', '<=', Carbon::now())
            ->where('estado_activacion', 'APROBADO')
            ->get();
        $ventasPorMes = VentaResource::collection($ventasPorMes);
        $ventasPorPlanes = VentaResource::collection($ventasPorPlanes);
        // Generar data para graficos estadisticos
        $ventasPorEstadoBar = [
            'labels' => $graficoVentasPorEstado->pluck('estado_activacion'),
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
                    'data' => array_values($graficoVentasPorPlanes),
                ],
            ],
        ];
        $ventasTiemposLine = [
            'labels' => $ventas_mes->pluck('mes'),
            'datasets' => [
                [
                    'label' => "Cantidad de ventas por mes",
                    'backgroundColor' => [
                        "#D62C18 ",
                    ],
                    'data' => $ventas_mes->pluck('total_ventas'),
                ],
            ],
        ];

        return compact(
            'cantidad_ventas',
            'cantidad_ventas_instaladas',
            'cantidad_ventas_por_instalar',
            'ventasPorEstado',
            'ventasPorPlanes',
            'ventasPorEstadoBar',
            'ventasPorPlanesoBar',
            'ventasTiemposLine',
            'ventasPorMes'
        );
    }
}
