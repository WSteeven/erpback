<?php

namespace Src\App\Tareas;

use App\Models\Autorizacion;
use App\Models\Cliente;
use App\Models\DetalleProducto;
use App\Models\DetalleProductoTransaccion;
use App\Models\EstadoTransaccion;
use App\Models\Inventario;
use App\Models\ItemDetallePreingresoMaterial;
use App\Models\MaterialEmpleado;
use App\Models\PreingresoMaterial;
use App\Models\Producto;
use App\Models\SeguimientoMaterialStock;
use App\Models\Tareas\TransferenciaProductoEmpleado;
use App\Models\TransaccionBodega;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Src\App\TransaccionBodegaEgresoService;
use Src\Config\EstadosTransacciones;
use Src\Shared\Utils;

class ProductoEmpleadoService
{
    private $servicio;

    public function __construct()
    {
        $this->servicio = new TransaccionBodegaEgresoService();
    }

    public function obtenerProductos()
    {
        // try {
            //code...
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
                //buscamos los egresos donde el producto conste para el empleado y el cliente dado
                $ids_inventarios = Inventario::where('detalle_id', $detalle->id)->pluck('id');
                $ids_transacciones = TransaccionBodega::where('responsable_id', request()->empleado_id)->where('estado_id', EstadosTransacciones::COMPLETA)->pluck('id');
                $ids_egresos = DetalleProductoTransaccion::whereIn('inventario_id', $ids_inventarios)->whereIn('transaccion_id', $ids_transacciones)->pluck('transaccion_id');
                $ids_preingresos = PreingresoMaterial::where('responsable_id', request()->empleado_id)->where('autorizacion_id', Autorizacion::APROBADO_ID)->pluck('id');
                $ids_items_preingresos = ItemDetallePreingresoMaterial::where('detalle_id', $detalle->id)->whereIn('preingreso_id', $ids_preingresos)->pluck('preingreso_id');
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
                    'transacciones' => count($ids_egresos) > 0 ? 'Egresos:' . $ids_egresos .'; '.(count($ids_items_preingresos)>0?'Preingresos:'.$ids_items_preingresos:''):'',
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
        // } catch (\Throwable $th) {
        //     throw ValidationException::withMessages(['error' => $th->getLine() . '. ' . $th->getMessage()]);
        // }
    }
}
