<?php

namespace Src\App\ComprasProveedores;

use App\Http\Resources\ComprasProveedores\PrefacturaResource;
use App\Models\ComprasProveedores\Prefactura;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class PrefacturaService
{

    public function __construct()
    {
        //
    }

    public function filtrarPrefacturas(Request $request)
    {
        $results = collect([]);
        $fecha_inicio = date('Y-m-d', strtotime($request->fecha_inicio));
        $fecha_fin = date('Y-m-d', strtotime($request->fecha_fin));
        $prefacturas = Prefactura::whereBetween('created_at', [$fecha_inicio, $fecha_fin])
            ->when($request->cliente, function ($query) use ($request) {
                $query->where('cliente_id', $request->cliente);
            })
            ->get();

        if ($request->estado) {
            if (in_array('2', $request->estado)) $results = $results->merge($prefacturas->filter(function ($prefactura) {
                return $prefactura->estado_id === 2;
            }));
            if (in_array('4', $request->estado)) $results = $results->merge($prefacturas->filter(function ($prefactura) {
                return $prefactura->estado_id === 4;
            }));
        } else {
            $results = $prefacturas;
        }

        return $results->unique();
    }

    public static function obtenerDashboard(Request $request)
    {
        $results = [];
        $fecha_inicio = date('Y-m-d', strtotime($request->fecha_inicio));
        $fecha_fin = date('Y-m-d', strtotime($request->fecha_fin));
        $prefacturas = Prefactura::whereBetween('created_at', [$fecha_inicio, $fecha_fin])
            ->when($request->empleado, function ($query) use ($request) {
                $query->where('solicitante_id', $request->empleado);
            })
            ->when($request->cliente, function ($query) use ($request) {
                $query->where('cliente_id', $request->cliente);
            })
            ->get();

        // Log::channel('testing')->info('Log', ['obtener ordenes por estados:', $fecha_inicio, $fecha_fin, $ordenes]);

        $creadas = $prefacturas->filter(function ($prefactura) {
            return $prefactura->estado_id === 2;
        });
        $anuladas = $prefacturas->filter(function ($prefactura) {
            return $prefactura->estado_id === 4;
        });
        $todas = PrefacturaResource::collection($prefacturas);
        $creadas = PrefacturaResource::collection($creadas);
        $anuladas = PrefacturaResource::collection($anuladas);
        $cant_prefacturas_creadas = $creadas->count();
        $cant_prefacturas_anuladas = $anuladas->count();
        $tituloGrafico = "Prefacturas";

        $graficos = [];

        //Configuramos el primer gráfico
        $graficoCreadas = new Collection([
            'id' => 1,
            'identificador' => 'CREADAS',
            'encabezado' => 'Estados de autorización de las ordenes de compras',
            'labels' => ['CREADAS', 'ANULADAS'],
            'datasets' => [
                [
                    'backgroundColor' => Utils::colorDefault(),
                    'label' => $tituloGrafico,
                    'data' => [$cant_prefacturas_creadas, $cant_prefacturas_anuladas],
                ]
            ],
        ]);
        array_push($graficos, $graficoCreadas);

        return compact(
            'graficos',
            'todas',
            'creadas',
            'anuladas',
            'cant_prefacturas_creadas',
            'cant_prefacturas_anuladas',
        );;
    }
}
