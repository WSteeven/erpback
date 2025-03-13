<?php

namespace Src\App\Tareas;

use App\Models\Autorizacion;
use App\Models\DetalleProductoTransaccion;
use App\Models\Inventario;
use App\Models\ItemDetallePreingresoMaterial;
use App\Models\PreingresoMaterial;
use App\Models\SeguimientoMaterialSubtarea;
use App\Models\TransaccionBodega;
use Src\App\TransaccionBodegaEgresoService;
use App\Models\MaterialEmpleadoTarea;
use App\Models\DetalleProducto;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Tarea;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Src\Config\EstadosTransacciones;

// Proyectos, Etapas y Tareas
class ProductoTareaEmpleadoService
{
    private $servicio;

    public function __construct()
    {
        $this->servicio = new TransaccionBodegaEgresoService();
    }

    public function obtenerProductos()
    {
        request()->validate([
            'proyecto_id' => 'nullable|numeric|integer',
            'etapa_id' => 'nullable|numeric|integer',
            'tarea_id' => 'nullable|numeric|integer',
            'subtarea_id' => 'nullable|numeric|integer',
            'empleado_id' => 'required|numeric|integer',
        ]);

        $proyecto_id = request('proyecto_id');
        $etapa_id = request('etapa_id');
        $tarea_id = request('tarea_id');

        $results = request()->exists('subtarea_id') ? $this->listarMaterialesSeguimiento() : $this->listarProductosConStock($proyecto_id, $etapa_id, $tarea_id);

        $materialesUtilizadosHoy = $this->obtenerMaterialesUsadosHoy();

        $materialesTarea = $this->mapearCantidadUtilizadaHoyMaterialesTarea($results, $materialesUtilizadosHoy);

        // Si es en el seguimiento entonces se hace el calculo de la suma de todo el material ocupado hasta el dia actual
        if (request('subtarea_id')) {
            return $this->mapearSumaMaterialUtilizadoSeguimiento($materialesTarea);
            // return response()->json(compact('results'));
        }

        return $materialesTarea;
    }

    // Si es para el seguimiento se listan sólo los productos con categoria materiales con stock mayor e igual a cero
    private function listarMaterialesSeguimiento()
    {
        $ignoreRequest = ['subtarea_id'];

        if (!request('etapa_id')) array_push($ignoreRequest, 'etapa_id');
        if (!request('cliente_id')) {
            if (!request('etapa_id')) $results = MaterialEmpleadoTarea::ignoreRequest($ignoreRequest)->filter()->where('cliente_id', '=', null)->where('etapa_id', null)->materiales()->get();
            else $results = MaterialEmpleadoTarea::ignoreRequest($ignoreRequest)->filter()->where('cliente_id', '=', null)->materiales()->get();
        } else {
            if (!request('etapa_id')) $results = MaterialEmpleadoTarea::ignoreRequest($ignoreRequest)->where('etapa_id', null)->filter()->materiales()->get();
            else $results = MaterialEmpleadoTarea::ignoreRequest($ignoreRequest)->filter()->materiales()->get();
        }

        return $results;
    }

    // Caso contrario se listan todos los productos con stock mayor a cero
    private function listarProductosConStockOld()
    {
        $proyecto_id = request('proyecto_id');
        $etapa_id = request('etapa_id');
        $tarea_id = request('tarea_id');

        $ignoreRequest = ['proyecto_id', 'etapa_id']; //!request('etapa_id') ? ['etapa_id'] : [];
        $consulta = MaterialEmpleadoTarea::ignoreRequest($ignoreRequest)->filter()->tieneStock();
        if (request('proyecto_id') && !request('etapa_id')) $consulta = $consulta->deProyecto($proyecto_id);
        else if (request('proyecto_id') && request('etapa_id')) $consulta = $consulta->deEtapa($proyecto_id, $etapa_id);
        else if (!request('proyecto_id') && !request('etapa_id') && request('tarea_id')) $consulta = $consulta->deTarea($tarea_id);

        if (!$this->tieneCliente()) {

            $consulta = $consulta->where('cliente_id', '=', null);
            // $results = $consulta->get();
            // if (!request('etapa_id')) $results = MaterialEmpleadoTarea::ignoreRequest($ignoreRequest)->where('etapa_id', null)->filter()->tieneStock()->get();
            // $results = MaterialEmpleadoTarea::ignoreRequest($ignoreRequest)->filter()->tieneStock()->get();
            // } else {
            // if (!request('etapa_id')) $results = MaterialEmpleadoTarea::ignoreRequest($ignoreRequest)->filter()->where('cliente_id', null)->where('etapa_id', null)->tieneStock()->get();
            // $results = MaterialEmpleadoTarea::ignoreRequest($ignoreRequest)->filter()->where('cliente_id', '=', null)->tieneStock()->get();
        }

        $sql = $consulta->toSql();
        Log::channel('testing')->info('Log', compact('sql'));
        $results = $consulta->get();
        return $results;
    }

    public function listarProductosConStock($proyecto_id, $etapa_id, $tarea_id)
    {
        $ignoreRequest = ['proyecto_id', 'etapa_id'];
        $consulta = MaterialEmpleadoTarea::ignoreRequest($ignoreRequest)->filter()->tieneStock();
        if ($proyecto_id && !$etapa_id) $consulta = $consulta->deProyecto($proyecto_id);
        else if ($proyecto_id && $etapa_id) $consulta = $consulta->deEtapa($proyecto_id, $etapa_id);
        else if (!$proyecto_id && !$etapa_id && $tarea_id) $consulta = $consulta->deTarea($tarea_id);

        if (!$this->tieneCliente()) $consulta = $consulta->where('cliente_id', '=', null);

        $results = $consulta->get();
        // Log::channel('testing')->info('Log', compact('results'));
        return $results;
    }

    private function tieneCliente()
    {
        return request('cliente_id');
    }

    // Se mapean los campos y se coloca en 'cantidad utilizada' la cantidad usada en el dia actual
    private function mapearCantidadUtilizadaHoyMaterialesTarea($results, $materialesUtilizadosHoy)
    {
        return collect($results)->map(function ($item, $index) use ($materialesUtilizadosHoy) {
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
                'cantidad_utilizada' => $materialesUtilizadosHoy->first(fn($material) => $material->detalle_producto_id == $item->detalle_producto_id)?->cantidad_utilizada,
                'transacciones' => count($ids_egresos) > 0 ? 'Egresos:' . $ids_egresos . '; ' . (count($ids_items_preingresos) > 0 ? 'Preingresos:' . $ids_items_preingresos : '') : '',
                'medida' => $producto->unidadMedida?->simbolo,
                'serial' => $detalle->serial,
                'cliente' => Cliente::find($item->cliente_id)?->empresa->razon_social,
                'cliente_id' => $item->cliente_id,
                'codigo_tarea' => Tarea::find($item->tarea_id)?->codigo_tarea,
                'tarea_id' => $item->tarea_id,
            ];
        });
    }

    private function mapearSumaMaterialUtilizadoSeguimiento($materialesTarea)
    {
        $sumaMaterialesUsados = $this->servicio->obtenerSumaMaterialTareaUsado(request('subtarea_id'), request('empleado_id'));

        $results = $materialesTarea->map(function ($materialOcupadoFecha) use ($sumaMaterialesUsados) {
            if ($sumaMaterialesUsados->contains('detalle_producto_id', $materialOcupadoFecha['detalle_producto_id']) && $sumaMaterialesUsados->contains('cliente_id', $materialOcupadoFecha['cliente_id'])) {
                $materialOcupadoFecha['total_cantidad_utilizada'] = $sumaMaterialesUsados->first(function ($item) use ($materialOcupadoFecha) {
                    return $item->detalle_producto_id === $materialOcupadoFecha['detalle_producto_id'] && $item->cliente_id === $materialOcupadoFecha['cliente_id'];
                })->suma_total;
            }
            return $materialOcupadoFecha;
        });

        $results = $results->sortByDesc(function ($elemento) {
            // Ordena por cantidad_utilizada y coloca aquellos sin valor al final
            return is_null($elemento['cantidad_utilizada']) ? -PHP_INT_MAX : $elemento['cantidad_utilizada'];
        })->toArray();

        return array_values($results);
    }

    // Obtener los materiales utilizados en el dia actual
    private function obtenerMaterialesUsadosHoy()
    {
        return SeguimientoMaterialSubtarea::where('empleado_id', request('empleado_id'))
            ->where('subtarea_id', request('subtarea_id'))
            ->where('cliente_id', request('cliente_id'))
            ->whereDate('created_at', Carbon::now()->format('Y-m-d'))
            ->get();
    }

    public function filtrarMaterialesEquipos($productos) // Destino Tarea
    {
        return $productos->filter(function ($producto) {
            return in_array($producto['categoria'], ['MATERIAL', 'EQUIPO']);
        });
    }
    public function filtrarHerramientasAccesoriosEquiposPropios($productos) // Destino Stock
    {
        return $productos->filter(function ($producto) {
            return in_array($producto['categoria'], ['HERRAMIENTA', 'ACCESORIO', 'EQUIPO PROPIO', 'INFORMATICA', 'SUMINISTRO', 'EQUIPO PARA ALOJAMIENTO', 'MUEBLE Y ENSERES', 'BOTIQUIN', 'MAQUINA']);
        });
    }
}
