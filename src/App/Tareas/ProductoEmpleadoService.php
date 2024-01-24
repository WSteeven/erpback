<?php

namespace Src\App\Tareas;

use App\Models\Autorizacion;
use App\Models\Cliente;
use App\Models\DetalleProducto;
use App\Models\MaterialEmpleado;
use App\Models\Producto;
use App\Models\SeguimientoMaterialStock;
use App\Models\Tareas\TransferenciaProductoEmpleado;
use App\Models\User;
use Carbon\Carbon;
use Src\App\TransaccionBodegaEgresoService;

class ProductoEmpleadoService
{
    private $servicio;

    public function __construct()
    {
        $this->servicio = new TransaccionBodegaEgresoService();
    }

    public function obtenerProductos()
    {
        request()->validate([
            'empleado_id' => 'required|numeric|integer',
            'seguimiento' => 'nullable|boolean',
        ]);

        if (request()->exists('subtarea_id')) {
            // Log::channel('testing')->info('Log', ['en el if', 'ifff..']);
            // Cuando se hace el seguimiento de la subtarea solo se deben descontar los materiales
            if (!request('cliente_id')) $results = MaterialEmpleado::ignoreRequest(['subtarea_id', 'seguimiento'])->filter()->where('cliente_id', '=', null)->materiales()->get();
            else $results = MaterialEmpleado::ignoreRequest(['subtarea_id', 'seguimiento'])->filter()->materiales()->get();
        } else {
            // Log::channel('testing')->info('Log', ['en el else', 'else..']);
            // Mi bodega
            if (!request('cliente_id')) $results = MaterialEmpleado::ignoreRequest(['subtarea_id'])->filter()->where('cliente_id', '=', null)->tieneStock()->get();
            else $results = MaterialEmpleado::ignoreRequest(['subtarea_id'])->filter()->tieneStock()->get();
        }

        $materialesUtilizadosHoy = SeguimientoMaterialStock::where('empleado_id', request('empleado_id'))->where('subtarea_id', request('subtarea_id'))->whereDate('created_at', Carbon::now()->format('Y-m-d'))->get();

        $materiales = collect($results)->map(function ($item) use ($materialesUtilizadosHoy) {
            $detalle = DetalleProducto::find($item->detalle_producto_id);
            $producto = Producto::find($detalle->producto_id);

            return [
                'id' => $item->detalle_producto_id,
                'producto' => $producto->nombre,
                'detalle_producto' => $detalle->descripcion,
                'detalle_producto_id' => $item->detalle_producto_id,
                'categoria' => $detalle->producto->categoria->nombre,
                'stock_actual' => intval($item->cantidad_stock),
                'despachado' => intval($item->despachado),
                'devuelto' => intval($item->devuelto),
                'cantidad_utilizada' => $materialesUtilizadosHoy->first(fn ($material) => $material->detalle_producto_id == $item->detalle_producto_id)?->cantidad_utilizada,
                'medida' => $producto->unidadMedida?->simbolo,
                'serial' => $detalle->serial,
                'cliente' => Cliente::find($item->cliente_id)?->empresa->razon_social,
            ];
        });

        // En el seguimiento - se ordena por cantidad utilizada en el dia
        if (request('subtarea_id')) {
            $materialesUsados = $this->servicio->obtenerSumaMaterialStockUsado(request('subtarea_id'), request('empleado_id'));
            $results = $materiales->map(function ($material) use ($materialesUsados) {
                if ($materialesUsados->contains('detalle_producto_id', $material['detalle_producto_id'])) {
                    $material['total_cantidad_utilizada'] = $materialesUsados->first(function ($item) use ($material) {
                        return $item->detalle_producto_id === $material['detalle_producto_id'];
                    })->suma_total;
                }
                return $material;
            });

            $results = $results->sortByDesc(function ($elemento) {
                // Ordena por cantidad_utilizada y coloca aquellos sin valor al final
                return is_null($elemento['cantidad_utilizada']) ? -PHP_INT_MAX : $elemento['cantidad_utilizada'];
            })->toArray();

            return array_values($results);
            // return response()->json(compact('results'));
        }

        $results = $materiales->toArray();
        return array_values($results);
    }
}
