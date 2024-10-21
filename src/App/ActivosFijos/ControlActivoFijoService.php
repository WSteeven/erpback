<?php

namespace Src\App\ActivosFijos;

use App\Exports\ActivosFijos\ReporteActivosFijosExport;
use App\Models\ActivosFijos\SeguimientoConsumoActivosFijos;
use App\Models\DetalleProducto;
use App\Models\DetalleProductoTransaccion;
use App\Models\Inventario;
use App\Models\MaterialEmpleado;
use App\Models\Motivo;
use App\Models\TipoTransaccion;
use App\Models\TransaccionBodega;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\Sistema\PaginationService;
use Maatwebsite\Excel\Facades\Excel;
use Src\Config\EstadosTransacciones;

class ControlActivoFijoService
{
    protected PaginationService $paginationService;

    public function __construct()
    {
        $this->paginationService = new PaginationService();
    }

    public function descargarReporte()
    {
        if (request('export') == 'xlsx') return $this->descargarReporteActivosFijos();
    }

    public function descargarReporteActivosFijos()
    {
        $detallesProductosActivos = DetalleProducto::pluck('id');
        $listado = MaterialEmpleado::select('e.nombres', 'e.apellidos', 'dp.descripcion', 'dp.serial', 'empr.razon_social as cliente', 'cantidad_stock', 'dp.id as detalle_producto_id', 'c.id as cliente_id', 'empleado_id', 'cantones.canton', 'prod.nombre as producto', 'dp.codigo_activo_fijo')
            ->join('empleados as e', 'materiales_empleados.empleado_id', 'e.id')
            ->join('detalles_productos as dp', 'materiales_empleados.detalle_producto_id', 'dp.id')
            ->join('productos as prod', 'dp.producto_id', 'prod.id')
            ->join('clientes as c', 'materiales_empleados.cliente_id', 'c.id')
            ->join('empresas as empr', 'c.empresa_id', 'empr.id')
            ->join('cantones', 'e.canton_id', 'cantones.id')
            ->whereIn('detalle_producto_id', $detallesProductosActivos)
            ->where('cantidad_stock', '>', '0')
            ->get();
        Log::channel('testing')->info('');

        $listado = $listado->map(function ($material) {
            $material['transaccion_egreso'] = $this->obtenerInformacionTransaccionEgreso($material->detalle_producto_id, $material->cliente_id, $material->empleado_id);
            $material['transaccion_ingreso'] = $this->obtenerInformacionTransaccionIngreso($material->detalle_producto_id, $material->cliente_id);
            return $material;
        });

        // return response()->json(compact('listado'));
        $export = new ReporteActivosFijosExport($listado, 'Reporte activos fijos');
        return Excel::download($export, 'reporte_materiales.xlsx');
    }

    private function obtenerInformacionTransaccionEgreso(int $detalle_producto_id, int $cliente_id, int $responsable_id)
    {
        return TransaccionBodega::select('id', 'comprobante', 'created_at')
            ->whereIn('id', DetalleProductoTransaccion::whereIn('inventario_id', Inventario::where('detalle_id', $detalle_producto_id)->where('cliente_id', $cliente_id)->pluck('id'))->pluck('transaccion_id'))
            ->whereIn('motivo_id', Motivo::where('tipo_transaccion_id', TipoTransaccion::where('nombre', 'EGRESO')->pluck('id'))->pluck('id'))
            ->where('estado_id', EstadosTransacciones::COMPLETA)
            ->where('responsable_id', $responsable_id)
            ->orderBy('created_at', 'DESC')
            ->first();
    }

    private function obtenerInformacionTransaccionIngreso(int $detalle_producto_id, int $cliente_id)
    {
        return TransaccionBodega::select('id', 'comprobante', 'created_at')
            ->whereIn('id', DetalleProductoTransaccion::whereIn('inventario_id', Inventario::where('detalle_id', $detalle_producto_id)->where('cliente_id', $cliente_id)->pluck('id'))->pluck('transaccion_id'))
            ->whereIn('motivo_id', Motivo::where('tipo_transaccion_id', TipoTransaccion::where('nombre', 'INGRESO')->pluck('id'))->pluck('id'))
            ->orderBy('created_at', 'DESC')
            ->first();
    }
}
