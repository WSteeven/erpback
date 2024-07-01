<?php

namespace Src\App\Tareas;

use App\Models\Autorizacion;
use App\Models\Cliente;
use App\Models\DetalleDevolucionProducto;
use App\Models\DetalleProducto;
use App\Models\DetalleProductoTransaccion;
use App\Models\Devolucion;
use App\Models\Empleado;
use App\Models\Empresa;
use App\Models\EstadoTransaccion;
use App\Models\Inventario;
use App\Models\ItemDetallePreingresoMaterial;
use App\Models\Motivo;
use App\Models\SeguimientoMaterialStock;
use App\Models\SeguimientoMaterialSubtarea;
use App\Models\Subtarea;
use App\Models\Tareas\DetalleTransferenciaProductoEmpleado;
use App\Models\Tareas\TransferenciaProductoEmpleado;
use App\Models\PreingresoMaterial;
use App\Models\Tarea;
use App\Models\TipoTransaccion;
use App\Models\TransaccionBodega;
use Illuminate\Support\Facades\Log;
use Src\Config\EstadosTransacciones;

class MaterialesUtilizadosStockService
{
    private $reporte = [];

    public function init()
    {
        $proyecto_id = request('proyecto_id');
        $tarea_id = request('tarea_id');

        // $this->reporte['materiales_utilizados_stock'] = self::obtenerMaterialesUtilizadosSubtareas($tarea_id);
        $this->reporte['materiales_utilizados_stock'] = self::obtenerMaterialesUtilizadosStock($proyecto_id, $tarea_id);
        $this->reporte['transferencias_recibidas'] = []; // self::obtenerMaterialesTransferenciasRecibidas($proyecto_id, $tarea_id);
        $this->reporte['transferencias_enviadas'] = []; // self::obtenerMaterialesTransferenciasEnviadas($proyecto_id, $tarea_id);
        $this->reporte['ingresos_bodega'] = []; // self::obtenerMaterialesIngresadosABodega($proyecto_id, $tarea_id);
        $this->reporte['egresos_bodega'] = []; // self::obtenerMaterialesEgresadosDeBodega($proyecto_id, $tarea_id);
        $this->reporte['devoluciones'] = []; // self::obtenerMaterialesDevueltosABodega($proyecto_id, $tarea_id);
        $this->reporte['preingresos'] = []; // self::obtenerMaterialesIngresadosPorPreingresos($proyecto_id, $tarea_id);

        Log::channel('testing')->info('Log', ['materiales_utilizados_stock', $this->reporte['materiales_utilizados_stock']]);

        $this->reporte['encabezado_subtareas'] = collect($this->reporte['materiales_utilizados_stock'])->map(fn ($item) => [
            'id' => $item['subtarea_id'],
            'codigo_subtarea' => $item['codigo_subtarea'],
        ])->unique();

        $this->reporte['encabezado_transferencias_recibidas'] = collect($this->reporte['transferencias_recibidas'])->map(fn ($item) => [
            'id' => $item['transferencia_id'],
            'codigo_transferencia' => 'TRANSF-PROD-' . $item['transferencia_id'],
        ])->unique();

        $this->reporte['encabezado_egresos_bodega'] = collect($this->reporte['egresos_bodega'])->map(fn ($item) => [
            'id' => $item['egreso_id'],
            'codigo_egreso' => $item['motivo'] . '-' . $item['egreso_id'],
        ])->unique();

        $this->reporte['encabezado_ingresos'] = collect($this->reporte['ingresos_bodega'])->map(fn ($item) => [
            'id' => $item['egreso_id'],
            'codigo_ingreso' => $item['motivo'] . '-' . $item['egreso_id'],
        ])->unique();

        $this->reporte['encabezado_preingresos'] = collect($this->reporte['preingresos'])->map(fn ($item) => [
            'id' => $item['egreso_id'],
            'codigo_ingreso' => $item['motivo'] . '-' . $item['egreso_id'],
        ])->unique();

        $this->reporte['encabezado_devoluciones'] = collect($this->reporte['devoluciones'])->map(fn ($item) => [
            'id' => $item['egreso_id'],
            'codigo_devolucion' => $item['motivo'] . '-' . $item['egreso_id'],
        ])->unique();

        $this->reporte['todos_materiales'] = self::obtenerTodosMateriales();

        return $this->reporte;
    }

    /*************
     * Subtareas
     *************/
    public static function obtenerMaterialesUtilizadosStock(int|null $proyecto_id, int|null $tarea_id)
    {
        if ($proyecto_id) {
            $tareas_ids = Tarea::where('proyecto_id', $proyecto_id)->pluck('id');
            $subtareas_ids = Subtarea::whereIn('tarea_id', $tareas_ids)->pluck('id');
        } else {
            $subtareas_ids = Subtarea::where('tarea_id', $tarea_id)->pluck('id');
        }

//         $subtareas_ids = Subtarea::where('tarea_id', $tarea_id)->pluck('id');
        $seguimientos = SeguimientoMaterialStock::whereIn('subtarea_id', $subtareas_ids)->get();
        // Log::channel('testing')->info('Log', ['seguimientos stock', $seguimientos]);
        $data = self::empaquetarMaterialesUtilizados($seguimientos);
        // Log::channel('testing')->info('Log', ['data', $data]);
        return $data;
    }

    private static function empaquetarMaterialesUtilizados($seguimientos)
    {
        $resultadosAgrupados = [];

        foreach ($seguimientos as $seguimiento) {
            $detalleProducto = $seguimiento->detalleProducto;

            if ($detalleProducto) {
                $clave = $detalleProducto->id . '-' . $seguimiento->cliente_id . '-' . $seguimiento->subtarea_id;

                if (!isset($resultadosAgrupados[$clave])) {
                    $resultadosAgrupados[$clave] = [
                        'detalle_id' => $detalleProducto->id,
                        'cliente_id' => $seguimiento->cliente_id,
                        'cliente' => $seguimiento->cliente?->empresa->razon_social,
                        'subtarea_id' => $seguimiento->subtarea_id,
                        'codigo_subtarea' => $seguimiento->subtarea->codigo_subtarea,
                        'detalle' => $detalleProducto->descripcion,
                        'unidad' => $detalleProducto->producto->unidadMedida->simbolo,
                        'cantidad' => 0,
                    ];
                }

                $resultadosAgrupados[$clave]['cantidad'] += $seguimiento->cantidad_utilizada;
            }
        }

        // Convertir el array asociativo en un array numérico
        $resultados = array_values($resultadosAgrupados);
        return $resultados;
    }

    public function obtenerCantidadMaterialPorSubtarea($detalle_producto_id, $cliente_id, $subtarea_id)
    {
        $encontrado = collect($this->reporte['materiales_utilizados_stock'])->first(fn ($material) =>
        $material['detalle_id'] == $detalle_producto_id
            && $material['cliente_id'] == $cliente_id
            && $material['subtarea_id'] == $subtarea_id);

        return $encontrado ? $encontrado['cantidad'] : '-';
    }

    public function obtenerSumaMaterialPorDetalleCliente($detalle_producto_id, $cliente_id)
    {
        $encontrado = collect($this->reporte['materiales_utilizados_stock'])->filter(fn ($material) =>
        $material['detalle_id'] == $detalle_producto_id
            && $material['cliente_id'] == $cliente_id);

        // Log::channel('testing')->info('Log', ['Encontrado', $detalle_producto_id, $cliente_id]);
        // Log::channel('testing')->info('Log', ['Encontrado', $encontrado]);
        return $encontrado ? $encontrado->sum('cantidad') : '-';
    }

    private function obtenerTodosMateriales()
    {
        $materiales_utilizados_stock = self::mapearMateriales($this->reporte['materiales_utilizados_stock']);
        $materiales_transferencias_recibidas = self::mapearMateriales($this->reporte['transferencias_recibidas']);
        $egresos_bodega = self::mapearMateriales($this->reporte['egresos_bodega']);
        $preingresos = self::mapearMateriales($this->reporte['preingresos']);
        return collect([...$materiales_utilizados_stock, ...$materiales_transferencias_recibidas, ...$egresos_bodega, ...$preingresos])->unique(function ($item) {
            return $item['detalle_id'] . $item['cliente_id'];
        })->sortBy('detalle');
    }

    private function mapearMateriales($array)
    {
        return collect($array)->map(fn ($item) => [
            'detalle' => $item['detalle'],
            'detalle_id' => $item['detalle_id'],
            'cliente' => isset($item['cliente']) ? $item['cliente'] : '-',
            'cliente_id' => $item['cliente_id'],
            'unidad' => isset($item['unidad']) ? $item['unidad'] : '-',
        ]);
    }
}
