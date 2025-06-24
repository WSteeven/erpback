<?php

namespace Src\App\ActivosFijos;

use App\Exports\ActivosFijos\ReporteActivosFijosExport;
use App\Models\DetalleProducto;
use App\Models\DetalleProductoTransaccion;
use App\Models\Inventario;
use App\Models\MaterialEmpleado;
use App\Models\Motivo;
use App\Models\TipoTransaccion;
use App\Models\TransaccionBodega;
use Exception;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Src\App\Sistema\PaginationService;
use Src\Config\EstadosTransacciones;

class ControlActivoFijoService
{
    protected PaginationService $paginationService;

    public function __construct()
    {
        $this->paginationService = new PaginationService();
    }

    /**
     * @throws Exception
     */
    public function descargarReporte()
    {
//        if (request('export') == 'xlsx') return $this->descargarReporteActivosFijos();
        return match (request('export')) {
            'pdf' => throw new Exception('Aun no se ha creado un reporte pdf'),
            default => $this->descargarReporteActivosFijos() // excel
        };
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function descargarReporteActivosFijos()
    {
        $detallesProductosActivos = DetalleProducto::pluck('id');
        $listado = MaterialEmpleado::select(['e.nombres', 'e.apellidos',
            'dp.descripcion', 'dp.serial', 'dp.id as detalle_producto_id', 'dp.codigo_activo_fijo',
            'empr.razon_social as cliente', 'c.id as cliente_id',
            'cantidad_stock', 'empleado_id', 'cantones.canton',
            'prod.nombre as producto', 'cat.nombre as categoria',])
            ->join('empleados as e', 'materiales_empleados.empleado_id', 'e.id')
            ->join('detalles_productos as dp', 'materiales_empleados.detalle_producto_id', 'dp.id')
            ->join('productos as prod', 'dp.producto_id', 'prod.id')
            ->join('categorias as cat', 'prod.categoria_id', 'cat.id')
            ->join('clientes as c', 'materiales_empleados.cliente_id', 'c.id')
            ->join('empresas as empr', 'c.empresa_id', 'empr.id')
            ->join('cantones', 'e.canton_id', 'cantones.id')
            ->whereIn('detalle_producto_id', $detallesProductosActivos)
            ->where('cantidad_stock', '>', '0')
            ->get();
//        Log::channel('testing')->info('');

        $listado = $listado->map(function ($material) {
            $material['transaccion_egreso'] = $this->obtenerInformacionTransaccionEgreso($material->detalle_producto_id, $material->cliente_id, $material->empleado_id);
            $material['condicion'] = isset($material['transaccion_egreso'])?$material['transaccion_egreso']['condicion']:'NO ENCONTRADO';
            $material['transaccion_ingreso'] = $this->obtenerInformacionTransaccionIngreso($material->detalle_producto_id, $material->cliente_id);
            return $material;
        });

        // return response()->json(compact('listado'));
        $export = new ReporteActivosFijosExport($listado, 'Reporte activos fijos');
        return Excel::download($export, 'reporte_materiales.xlsx');
    }

    private function obtenerInformacionTransaccionEgreso(int $detalle_producto_id, int $cliente_id, int $responsable_id)
    {
        return TransaccionBodega::select(['transacciones_bodega.id', 'num_comprobante','transacciones_bodega.created_at', 'cond.nombre as condicion'])
            ->join('detalle_producto_transaccion as dpt', 'transacciones_bodega.id', 'dpt.transaccion_id')
            ->join('inventarios as i', 'dpt.inventario_id', 'i.id')
            ->join('condiciones_de_productos as cond', 'i.condicion_id', 'cond.id')
            ->whereIn('transacciones_bodega.id', DetalleProductoTransaccion::whereIn('inventario_id', Inventario::where('detalle_id', $detalle_producto_id)->where('cliente_id', $cliente_id)->pluck('id'))->pluck('transaccion_id'))
            ->whereIn('motivo_id', Motivo::where('tipo_transaccion_id', TipoTransaccion::where('nombre', 'EGRESO')->pluck('id'))->pluck('id'))
            ->where('estado_id', EstadosTransacciones::COMPLETA)
            ->where('responsable_id', $responsable_id)
            ->orderBy('created_at', 'DESC')
            ->first();
    }

    private function obtenerInformacionTransaccionIngreso(int $detalle_producto_id, int $cliente_id)
    {
        return TransaccionBodega::select('id', 'num_comprobante', 'created_at')
            ->whereIn('id', DetalleProductoTransaccion::whereIn('inventario_id', Inventario::where('detalle_id', $detalle_producto_id)->where('cliente_id', $cliente_id)->pluck('id'))->pluck('transaccion_id'))
            ->whereIn('motivo_id', Motivo::where('tipo_transaccion_id', TipoTransaccion::where('nombre', 'INGRESO')->pluck('id'))->pluck('id'))
            ->orderBy('created_at', 'DESC')
            ->first();
    }
}
