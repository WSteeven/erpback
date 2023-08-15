<?php

namespace App\Http\Controllers;

use App\Exports\SeguimientoExport;
use App\Http\Requests\SeguimientoSubtareaRequest;
use App\Http\Resources\SeguimientoSubtareaResource;
use App\Models\SeguimientoMaterialSubtarea;
use App\Models\SeguimientoSubtarea;
use App\Models\Subtarea;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Src\App\FondosRotativos\ReportePdfExcelService;
use Src\Shared\Utils;
use Illuminate\Support\Facades\Storage;
use Src\App\SeguimientoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\App\TransaccionBodegaEgresoService;

class SeguimientoSubtareaController extends Controller
{
    private $entidad = 'SeguimientoSubtarea';
    private $reporteService;
    private $seguimientoService;

    public function __construct()
    {
        $this->reporteService = new ReportePdfExcelService();
        $this->seguimientoService = new SeguimientoService();
    }

    public function index()
    {
        $results = SeguimientoSubtarea::filter()->get();
        return response()->json(compact('results'));
    }

    public function show(SeguimientoSubtarea $seguimiento)
    {
        $modelo = new SeguimientoSubtareaResource($seguimiento);
        return response()->json(compact('modelo'));
    }

    public function store(SeguimientoSubtareaRequest $request)
    {
        $datos = $request->validated();

        $seguimiento = SeguimientoSubtarea::create($datos);

        $subtarea = Subtarea::find($request->safe()->only(['subtarea'])['subtarea']);
        $subtarea->seguimiento_id = $seguimiento->id;
        $subtarea->save();

        // Guardar fotografias
        $this->seguimientoService->guardarFotografias($datos, $seguimiento);

        // Material empleado tarea
        $this->seguimientoService->descontarMaterialTareaOcupadoStore($request);
        $this->seguimientoService->registrarSeguimientoMaterialTareaOcupadoStore($request);

        // Material empleado stock
        $this->seguimientoService->descontarMaterialStockOcupadoStore($request);

        $modelo = new SeguimientoSubtareaResource($seguimiento->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function update(SeguimientoSubtareaRequest $request, SeguimientoSubtarea $seguimiento)
    {
        $datos = $request->validated();

        $seguimiento->update($datos);

        // Eliminar imagenes
        foreach ($seguimiento->trabajoRealizado as $trabajo) {
            $ruta = str_replace('storage', 'public', $trabajo->fotografia);
            Storage::delete($ruta);
        }

        // Eliminar fotografias anteriores
        $seguimiento->trabajoRealizado()->delete();

        // Guardar fotografias
        $this->seguimientoService->guardarFotografias($datos, $seguimiento);

        // Material empleado tarea
        $this->seguimientoService->registrarSeguimientoMaterialTareaOcupadoUpdate($request);
        $this->seguimientoService->descontarMaterialTareaOcupadoUpdate($request);

        // Material empleado stock
        $this->seguimientoService->descontarMaterialStockOcupadoUpdate($request);

        $modelo = new SeguimientoSubtareaResource($seguimiento->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }

    public function exportarSeguimiento(SeguimientoSubtarea $seguimiento)
    {
        $tipo = 'excel';

        $export_excel = new SeguimientoExport($seguimiento);
        $vista = 'exports.reportes.excel.seguimiento_subtarea';
        $nombre_reporte = 'Juan Reporte';
        return $this->reporteService->imprimir_reporte($tipo, 'A4', 'landscape', $seguimiento, $nombre_reporte, $vista, $export_excel);
    }

    public function obtenerHistorialMaterialTareaUsadoPorFecha(Request $request)
    {
        $request->validate([
            'subtarea_id' => 'required|numeric|integer',
            'empleado_id' => 'required|numeric|integer',
            'fecha' => 'required|string',
        ]);

        $fecha_convertida = Carbon::createFromFormat('d-m-Y', $request['fecha'])->format('Y-m-d');
        $idEmpleado = $request['empleado_id'];
        $idSubtarea = $request['subtarea_id'];
        $idTarea = Subtarea::find($idSubtarea)->tarea_id;

        /* $results = DB::table('seguimientos_materiales_subtareas as sms')
            ->select('dp.descripcion as detalle_producto', 'met.cantidad_stock as stock_actual', DB::raw('sms.cantidad_utilizada AS cantidad_utilizada'), 'met.despachado', 'met.devuelto', 'dp.id as detalle_producto_id')
            ->join('detalles_productos as dp', 'sms.detalle_producto_id', '=', 'dp.id')
            ->join('materiales_empleados_tareas as met', 'dp.id', '=', 'met.detalle_producto_id')
            ->whereDate('sms.created_at', $fecha_convertida)
            ->where('sms.empleado_id', $request['empleado_id'])
            ->where('subtarea_id', $request['subtarea_id'])
            ->groupBy('detalle_producto_id')
            ->get(); */

        $results = DB::table('seguimientos_materiales_subtareas as sms')
            ->select('dp.descripcion as detalle_producto', 'met.cantidad_stock as stock_actual', 'sms.cantidad_utilizada', 'met.despachado', 'met.devuelto', 'dp.id as detalle_producto_id')
            ->join('detalles_productos as dp', 'sms.detalle_producto_id', '=', 'dp.id')
            ->join('materiales_empleados_tareas as met', function ($join) use ($idEmpleado, $idTarea) {
                $join->on('dp.id', '=', 'met.detalle_producto_id')
                    ->where('met.empleado_id', '=', $idEmpleado)
                    ->where('met.tarea_id', '=', $idTarea);
            })
            ->whereDate('sms.created_at', $fecha_convertida)
            ->where('sms.empleado_id', $idEmpleado)
            ->where('sms.subtarea_id', $idSubtarea)
            ->groupBy('detalle_producto_id')
            ->get();


        $servicio = new TransaccionBodegaEgresoService();
        $materialesUsados = $servicio->obtenerSumaMaterialTareaUsado($request['subtarea_id'], $request['empleado_id']);

        Log::channel('testing')->info('Log', compact('materialesUsados'));

        $results = $results->map(function ($material, $index) use ($materialesUsados) {
            if ($materialesUsados->contains('detalle_producto_id', $material->detalle_producto_id)) {
                $material->total_cantidad_utilizada = $materialesUsados->first(function ($item) use ($material) {
                    return $item->detalle_producto_id === $material->detalle_producto_id;
                })->suma_total;
            }
            $material->id = $index + 1;
            return $material;
        });

        return response()->json(compact('results'));
    }

    public function actualizarCantidadUtilizadaHistorial(Request $request)
    {
        $modelo = $this->seguimientoService->actualizarSeguimientoCantidadUtilizadaMaterialEmpleadoTarea($request);
        return response()->json(compact('modelo'));
    }
}
