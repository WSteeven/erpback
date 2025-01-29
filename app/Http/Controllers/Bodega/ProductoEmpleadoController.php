<?php

namespace App\Http\Controllers\Bodega;

use App\Exports\Bodega\MaterialesStockExport;
use App\Exports\TransaccionBodegaEgresoExport;
use App\Http\Controllers\Controller;
use App\Models\ConfiguracionGeneral;
use App\Models\MaterialEmpleado;
use App\Models\SeguimientoMaterialStock;
use App\Models\SeguimientoMaterialSubtarea;
use App\Models\TransaccionBodega;
use Illuminate\Http\Request;
use Log;
use Src\App\TransaccionBodegaEgresoService;
use Maatwebsite\Excel\Facades\Excel;
use Src\App\Bodega\PreingresoMaterialService;
use Src\App\Bodega\ProductoEmpleadoService;
use Src\App\Tareas\TransferenciaProductoEmpleadoService;
use Src\App\TransaccionBodegaIngresoService;

class ProductoEmpleadoController extends Controller
{
    private TransaccionBodegaEgresoService $transaccionBodegaEgresoService;
    private TransaccionBodegaIngresoService $transaccionBodegaIngresoService;
    private ProductoEmpleadoService $productoEmpleadoService;
    private TransferenciaProductoEmpleadoService $transferenciaProductoEmpleadoService;
    private PreingresoMaterialService $preingresoMaterialService;

    public function __construct()
    {
        $this->transaccionBodegaEgresoService = new TransaccionBodegaEgresoService();
        $this->transaccionBodegaIngresoService = new TransaccionBodegaIngresoService();
        $this->productoEmpleadoService = new ProductoEmpleadoService();
        $this->transferenciaProductoEmpleadoService = new TransferenciaProductoEmpleadoService();
        $this->preingresoMaterialService = new PreingresoMaterialService();
    }

    public function index(Request $request)
    {
        if (request('export') == 'xlsx') {
            return $this->reporteXlsx($request);
        }
    }

    // Log::channel('testing')->info('Log', ['Transf:', $transferencias_recibidas]);
    private function reporteXlsx(Request $request)
    {
        // EGRESOS
        $request['tipo'] = 3; //  Por responsable - egreso
        $results = $this->transaccionBodegaEgresoService->filtrarEgresoPorTipoFiltro($request);
        $egresos = TransaccionBodega::obtenerDatosReporteEgresos($results);
        $egresos_suma = $this->productoEmpleadoService->obtenerSumaReporteEgresos($results);

        // INGRESOS
        $request['tipo'] = 0; // Por solicitante - ingresos
        $request['solicitante'] = $request['responsable']; // Por solicitante - ingresos
        $results = $this->transaccionBodegaIngresoService->filtrarIngresoPorTipoFiltro($request);
        $ingresos = TransaccionBodega::obtenerDatosReporteIngresos($results);
        $ingresos_suma = $this->productoEmpleadoService->obtenerSumaReporteEgresos($results);

        // PREINGRESOS
        $preingresos = $this->preingresoMaterialService->filtrarPreingresosReporteExcel($request);
        $productos_preingresos = $this->preingresoMaterialService->obtenerProductosPreingresos($preingresos);
        $productos_preingresos_suma = $this->productoEmpleadoService->obtenerSumaCantidadesProductos($productos_preingresos);

        // TRANSFERENCIAS RECIBIDAS
        $transferencias_recibidas = $this->transferenciaProductoEmpleadoService->filtrarTransferenciasPorEmpleadoDestino($request);
        $productos_transferencias = $this->transferenciaProductoEmpleadoService->obtenerProductosTransferencia($transferencias_recibidas);
        $productos_transferencias_suma = $this->productoEmpleadoService->obtenerSumaCantidadesProductos($productos_transferencias);

        // TRANSFERENCIAS ENVIADAS
        $transferencias_enviadas = $this->transferenciaProductoEmpleadoService->filtrarTransferenciasPorEmpleadoOrigen($request);
        $productos_transferencias_enviadas = $this->transferenciaProductoEmpleadoService->obtenerProductosTransferencia($transferencias_enviadas);
        $productos_transferencias_enviadas_suma = $this->productoEmpleadoService->obtenerSumaCantidadesProductos($productos_transferencias_enviadas);

        // CONSUMO DE MATERIALES stock EN SUBTAREAS
        $seguimientos_materiales_stock = SeguimientoMaterialStock::filtrarSeguimientoMaterialExcel($request);
        $seguimientos_materiales_stock_suma = $this->productoEmpleadoService->obtenerSumaCantidadesProductos($seguimientos_materiales_stock);
        
        // CONSUMO DE MATERIALES tarea EN SUBTAREAS
        $seguimientos_materiales_tarea = SeguimientoMaterialSubtarea::filtrarSeguimientoMaterialExcel($request);
        $seguimientos_materiales_tarea_suma = $this->productoEmpleadoService->obtenerSumaCantidadesProductos($seguimientos_materiales_tarea);

        // SUMA DE LO QUE RECIBIÓ
        $recibido = $this->productoEmpleadoService->obtenerSumaCantidadesProductos([...$egresos_suma, ...$productos_preingresos_suma, ...$productos_transferencias_suma, ...$seguimientos_materiales_tarea_suma]);
        $consumido = $this->productoEmpleadoService->obtenerSumaCantidadesProductos([...$productos_transferencias_enviadas_suma, ...$ingresos_suma, ...$seguimientos_materiales_stock_suma]);
        $diferencia = $this->productoEmpleadoService->restarSumaCantidadesProductos($recibido, $consumido);
        // Log::channel('testing')->info('Log', ['Suma:', $diferencia]);

        $materiales_stock = [
            'egresos' => collect($egresos),
            'egresos_suma' => collect($egresos_suma),
            'preingresos' => collect($productos_preingresos),
            'preingresos_suma' => collect($productos_preingresos_suma),
            'transferencias_recibidas' => collect($productos_transferencias),
            'transferencias_recibidas_suma' => collect($productos_transferencias_suma),
            'transferencias_enviadas' => collect($productos_transferencias_enviadas),
            'transferencias_enviadas_suma' => collect($productos_transferencias_enviadas_suma),
            'devoluciones' => collect($ingresos),
            'devoluciones_suma' => collect($ingresos_suma),
            'ocupado_en_tareas' => collect($seguimientos_materiales_stock),
            'seguimientos_materiales_stock_suma' => collect($seguimientos_materiales_stock_suma),
            'material_tarea_ocupado_en_tareas' => collect($seguimientos_materiales_tarea),
            'material_tarea_ocupado_en_tareas_suma' => collect($seguimientos_materiales_tarea_suma),
            'stock_actual' => collect($diferencia),
        ];
        return Excel::download(new MaterialesStockExport($materiales_stock), 'reporte.xlsx');
    }
}
