<?php

namespace Src\App\Tareas;

use App\Models\Autorizacion;
use App\Models\Cliente;
use App\Models\DetalleProducto;
use App\Models\DetalleProductoTransaccion;
use App\Models\Empleado;
use App\Models\Inventario;
use App\Models\ItemDetallePreingresoMaterial;
use App\Models\MaterialEmpleado;
use App\Models\PreingresoMaterial;
use App\Models\Producto;
use App\Models\SeguimientoMaterialStock;
use App\Models\TransaccionBodega;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Src\App\TransaccionBodegaEgresoService;
use Src\Config\EstadosTransacciones;

class ProductoEmpleadoService
{
    private $servicio;

    public function __construct()
    {
        $this->servicio = new TransaccionBodegaEgresoService();
    }

    /**
     * Lista los productos de stock del empleado seleccionado
     * @param int $empleado_id es el id del empleado resposanble
     * @param boolean $seguimiento *(pendiente verificar si se quita)
     * @param int $subtarea_id si tiene subtarea entonces se listan solo los *materiales en el seguimento
     * @param int $cliente_id es el id del cliente propietario del producto
     * @return array contiene el listado de productos de stock del empleado
     */
    public function obtenerProductos()
    {
        if (request()->exists('subtarea_id')) {
            // Se muestran los *materiales en el seguimiento de la subtarea
            // Cuando se hace el seguimiento de la subtarea solo se deben descontar los *materiales por lo tanto solo esos se muestran
            $results = MaterialEmpleado::ignoreRequest(['subtarea_id', 'seguimiento'])->filter()->where('cliente_id', request('cliente_id'))->materiales()->get();
        } else {
            // Mi bodega
            if (!request('cliente_id')) $results = MaterialEmpleado::ignoreRequest(['subtarea_id'])->filter()->where('cliente_id', '=', null)->tieneStock()->get();
            else $results = MaterialEmpleado::ignoreRequest(['subtarea_id'])->filter()->tieneStock()->get();
        }

        $materialesUtilizadosHoy = SeguimientoMaterialStock::where('empleado_id', request('empleado_id'))->where('subtarea_id', request('subtarea_id'))->where('cliente_id', request('cliente_id'))->whereDate('created_at', Carbon::now()->format('Y-m-d'))->get();

        $materiales = collect($results)->map(function ($item) use ($materialesUtilizadosHoy) {
            /************************
             * Campo: Transacciones
             ************************/
            $detalle = DetalleProducto::find($item->detalle_producto_id);
            $producto = Producto::find($detalle->producto_id);
            
            // Buscamos los egresos donde el producto conste para el empleado y el cliente dado
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
                'transacciones' => count($ids_egresos) > 0 ? 'Egresos:' . $ids_egresos . '; ' . (count($ids_items_preingresos) > 0 ? 'Preingresos:' . $ids_items_preingresos : '') : '',
                'medida' => $producto->unidadMedida?->simbolo,
                'serial' => $detalle->serial,
                'cliente' => Cliente::find($item->cliente_id)?->empresa->razon_social,
                'cliente_id' => $item->cliente_id,
            ];
        });

        // En el seguimiento - se ordena por cantidad utilizada en el dia
        if (request('subtarea_id')) {
            $materialesUsados = $this->servicio->obtenerSumaMaterialStockUsado(request('subtarea_id'), request('empleado_id'), request('cliente_id'));
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
        }

        $results = $materiales->toArray();
        return array_values($results);
    }

    /**
     * @param int $detalle_producto_id id del detalle de producto
     * @param int $cliente_id id del cliente propietario del producto
     * @return array listado de asignaciones actuales del producto
     */
    public function obtenerAsignacionesProductos(int $detalle_producto_id, int $cliente_id, bool $seguimiento = null, bool $resumen = null)
    {
        $empleadosConEseDetalleProductoCliente = MaterialEmpleado::ignoreRequest(['resumen', 'seguimiento'])->filter()->tieneStock()->get();

        // $materialesUtilizadosHoy = SeguimientoMaterialStock::whereDate('created_at', Carbon::now()->format('Y-m-d'))->get();

        $materiales = collect($empleadosConEseDetalleProductoCliente)->map(function ($materialEmpleado) use ($detalle_producto_id, $cliente_id) {
            /************************
             * Campo: Transacciones
             ************************/
            $detalle = DetalleProducto::find($detalle_producto_id);
            $producto = Producto::find($detalle->producto_id);
            // Buscamos los egresos donde el producto conste para el empleado y el cliente dado
            $ids_inventarios = Inventario::where('detalle_id', $detalle->id)->pluck('id');
            $ids_transacciones = TransaccionBodega::where('responsable_id', request()->empleado_id)->where('estado_id', EstadosTransacciones::COMPLETA)->pluck('id');
            $ids_egresos = DetalleProductoTransaccion::whereIn('inventario_id', $ids_inventarios)->whereIn('transaccion_id', $ids_transacciones)->pluck('transaccion_id');
            $ids_preingresos = PreingresoMaterial::where('responsable_id', request()->empleado_id)->where('autorizacion_id', Autorizacion::APROBADO_ID)->pluck('id');
            $ids_items_preingresos = ItemDetallePreingresoMaterial::where('detalle_id', $detalle->id)->whereIn('preingreso_id', $ids_preingresos)->pluck('preingreso_id');

            return [
                'id' => $detalle->id,
                'producto' => $producto->nombre,
                'detalle_producto' => $detalle->descripcion,
                'detalle_producto_id' => $detalle_producto_id,
                'categoria' => $detalle->producto->categoria->nombre,
                'stock_actual' => intval($materialEmpleado->cantidad_stock),
                'despachado' => intval($materialEmpleado->despachado),
                'devuelto' => intval($materialEmpleado->devuelto),
                // 'cantidad_utilizada' => $materialesUtilizadosHoy->first(fn ($material) => $material->detalle_producto_id == $item->detalle_producto_id)?->cantidad_utilizada,
                'transacciones' => count($ids_egresos) > 0 ? 'Egresos:' . $ids_egresos . '; ' . (count($ids_items_preingresos) > 0 ? 'Preingresos:' . $ids_items_preingresos : '') : '',
                'medida' => $producto->unidadMedida?->simbolo,
                'serial' => $detalle->serial,
                'cliente' => Cliente::find($materialEmpleado->cliente_id)?->empresa->razon_social,
                'responsable' => Empleado::extraerNombresApellidos($materialEmpleado->empleado),
                'cliente_id' => $cliente_id,
            ];
        });

        if ($resumen) {
            $materialesUsados = $this->obtenerSumaActivoFijoConsumido($detalle_producto_id, $cliente_id);
            Log::channel('testing')->info('Log', ['materialesUsados', $materialesUsados]);
            $results = $materiales->map(function ($material) use ($materialesUsados) {
                if ($materialesUsados->contains('detalle_producto_id', $material['detalle_producto_id'])) {
                    $material['total_cantidad_utilizada'] = $materialesUsados->first(function ($item) use ($material) {
                        return $item->detalle_producto_id === $material['detalle_producto_id'];
                    })->suma_total;
                } else {
                    $material['total_cantidad_utilizada'] = 0;
                }
                return $material;
            });

            return $results;
        }

        // $results = $materiales->toArray();
        // return array_values($results);
        return $empleadosConEseDetalleProductoCliente;
    }

    /***************************************************************************************************
     * Devuelve un listado de los materiales de stock usados y su suma total por producto - Seguimiento
     ***************************************************************************************************/
    private function obtenerSumaActivoFijoConsumido(int $detalle_producto_id, int $cliente_id)
    {
        // $subtarea = Subtarea::find($idSubtarea);
        // $fecha_inicio = Carbon::parse($subtarea->fecha_hora_agendado)->format('Y-m-d');
        // $fecha_fin = $subtarea->fecha_hora_finalizacion ? Carbon::parse($subtarea->fecha_hora_finalizacion)->addDay()->format('Y-m-d') : Carbon::now()->addDay()->toDateString();

        return DB::table('af_seguimientos_consumo_activos_fijos as sms')
            ->select('dp.descripcion as producto', 'dp.id as detalle_producto_id', 'sms.cliente_id', DB::raw('SUM(sms.cantidad_utilizada) AS suma_total'))
            ->join('detalles_productos as dp', 'sms.detalle_producto_id', '=', 'dp.id')
            ->where('detalle_producto_id', $detalle_producto_id)
            ->where('cliente_id', $cliente_id)
            ->groupBy('empleado_id')
            ->get();
    }
}
