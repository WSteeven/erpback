<?php

namespace App\Http\Controllers;

use App\Exports\ReporteSubtareaExport;
use App\Exports\SeguimientoExport;
use App\Http\Requests\SeguimientoSubtareaRequest;
use App\Http\Resources\SeguimientoSubtareaResource;
use App\Models\Empleado;
use App\Models\MaterialEmpleadoTarea;
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
use Maatwebsite\Excel\Facades\Excel;

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

    /* public function index()
    {
        $results = SeguimientoSubtarea::filter()->get();
        return response()->json(compact('results'));
    } */

    /* public function show(SeguimientoSubtarea $seguimiento)
    {
        $modelo = new SeguimientoSubtareaResource($seguimiento);
        return response()->json(compact('modelo'));
    } */

    public function store(SeguimientoSubtareaRequest $request)
    {
        $datos = $request->validated();

        $seguimiento = SeguimientoSubtarea::create($datos);

        $subtarea = Subtarea::find($request->safe()->only(['subtarea'])['subtarea']);
        $subtarea->seguimiento_id = $seguimiento->id;
        $subtarea->save();

        // Guardar trabajos realizados
        $this->seguimientoService->guardarTrabajosRealizados($datos, $seguimiento);

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
        /* foreach ($seguimiento->trabajoRealizado as $trabajo) {
            $ruta = str_replace('storage', 'public', $trabajo->fotografia);
            Storage::delete($ruta);
        } */

        // Eliminar fotografias anteriores
        // $seguimiento->trabajoRealizado()->delete();

        // Guardar trabajos realizados - solo se guardan los nuevos que son pasados por el frontend, los anteriores se mantienen
        $this->seguimientoService->guardarTrabajosRealizados($datos, $seguimiento);

        // Material empleado tarea
        $this->seguimientoService->registrarSeguimientoMaterialTareaOcupadoUpdate($request);
        $this->seguimientoService->descontarMaterialTareaOcupadoUpdate($request);

        // Material empleado stock
        $this->seguimientoService->descontarMaterialStockOcupadoUpdate($request);

        $modelo = new SeguimientoSubtareaResource($seguimiento->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }

    public function exportarSeguimiento($subtarea_id)
    {
        $subtarea = Subtarea::find($subtarea_id);
        // $export_excel = new SeguimientoExport($subtarea);
        $export_excel = new ReporteSubtareaExport($subtarea);
        $nombre_reporte = 'Juan Reporte';
        return Excel::download($export_excel, $nombre_reporte . '.xlsx');
    }

    public function verSeguimiento(Subtarea $subtarea)
    {
        // $tipo = 'excel';

        // $export_excel = new SeguimientoExport($subtarea);
        $vista = 'exports.reportes.excel.seguimiento_subtarea';
        Log::channel('testing')->info('Log', compact('subtarea'));
        return view($vista, compact('subtarea'));
    }

    /******************
     * Material tarea
     ******************/
    public function obtenerResumenMaterialSeguimientoSubtareaUsado(Request $request)
    {
        $request->validate([
            'subtarea_id' => 'required|numeric|integer',
            'empleado_id' => 'required|numeric|integer',
        ]);

        return $this->consultarMaterialTareaUtilizado($request['subtarea_id'], $request['empleado_id']);
    }

    public function obtenerHistorialMaterialTareaUsadoPorFecha(Request $request)
    {
        $request->validate([
            'subtarea_id' => 'required|numeric|integer',
            'empleado_id' => 'required|numeric|integer',
            'fecha' => 'required|string',
        ]);

        return $this->consultarMaterialTareaUtilizado($request['subtarea_id'], $request['empleado_id'], $request['fecha']);
    }

    // Nuevo
    public function consultarMaterialTareaUtilizado($idSubtarea, $idEmpleado, $fecha = null)
    {
        $servicio = new TransaccionBodegaEgresoService();

        $fecha_convertida = $fecha ? Carbon::createFromFormat('d-m-Y', $fecha)->format('Y-m-d') : null;
        $idTarea = Subtarea::find($idSubtarea)->tarea_id;

        $materialesOcupadosFecha =  DB::table('seguimientos_materiales_subtareas as sms')->select('cantidad_utilizada', 'met.detalle_producto_id', 'met.cliente_id', 'met.empleado_id', 'met.despachado', 'met.cantidad_stock as stock_actual', 'met.devuelto', 'empresas.razon_social as cliente', 'dp.serial', 'unidades_medidas.simbolo as medida', 'clientes.id as cliente_id', 'dp.descripcion as detalle_producto')
            ->join('materiales_empleados_tareas as met', function ($query) use ($idEmpleado, $idTarea) {
                $query->on('met.detalle_producto_id', 'sms.detalle_producto_id')
                    ->on('met.cliente_id', '=', 'sms.cliente_id')
                    ->where('met.tarea_id', '=', $idTarea)
                    ->where('met.empleado_id', $idEmpleado);
            })
            ->join('detalles_productos as dp', 'sms.detalle_producto_id', '=', 'dp.id')
            ->join('clientes', 'sms.cliente_id', '=', 'clientes.id')
            ->join('empresas', 'clientes.empresa_id', '=', 'empresas.id')
            ->join('productos', 'dp.producto_id', '=', 'productos.id')
            ->join('unidades_medidas', 'productos.unidad_medida_id', 'unidades_medidas.id')
            ->when($fecha_convertida, function ($query, $fecha_convertida) {
                return $query->whereDate('sms.created_at', $fecha_convertida);
            })
            ->where('sms.empleado_id', $idEmpleado)
            ->where('sms.subtarea_id', $idSubtarea)
            ->get();

        $materialesOcupadosFechaSinCliente =  DB::table('seguimientos_materiales_subtareas as sms')->select('cantidad_utilizada', 'met.detalle_producto_id', 'met.cliente_id', 'met.empleado_id', 'met.despachado', 'met.cantidad_stock as stock_actual', 'met.devuelto', 'dp.serial', 'unidades_medidas.simbolo as medida', 'clientes.id as cliente_id', 'dp.descripcion as detalle_producto')
            ->join('materiales_empleados_tareas as met', function ($query) use ($idEmpleado, $idTarea) {
                $query->on('met.detalle_producto_id', 'sms.detalle_producto_id')
                    // ->whereNull('met.cliente_id')
                    ->whereNull('sms.cliente_id')
                    ->where('met.tarea_id', '=', $idTarea)
                    ->where('met.empleado_id', $idEmpleado);
            })
            ->join('detalles_productos as dp', 'sms.detalle_producto_id', '=', 'dp.id')
            ->leftJoin('clientes', 'sms.cliente_id', '=', 'clientes.id')
            ->leftJoin('empresas', 'clientes.empresa_id', '=', 'empresas.id')
            ->join('productos', 'dp.producto_id', '=', 'productos.id')
            ->join('unidades_medidas', 'productos.unidad_medida_id', 'unidades_medidas.id')
            ->when($fecha_convertida, function ($query, $fecha_convertida) {
                return $query->whereDate('sms.created_at', $fecha_convertida);
            })
            ->where('sms.empleado_id', $idEmpleado)
            ->where('sms.subtarea_id', $idSubtarea)
            ->get();
        // ->whereDate('sms.created_at', $fecha_convertida)

        $results = $materialesOcupadosFecha->merge($materialesOcupadosFechaSinCliente);
        $sumaMaterialesUsados = $servicio->obtenerSumaMaterialTareaUsadoHistorial($idSubtarea, $idEmpleado);

        $results = $results->map(function ($materialOcupadoFecha, $index) use ($sumaMaterialesUsados) {
            if ($sumaMaterialesUsados->contains('detalle_producto_id', $materialOcupadoFecha->detalle_producto_id) && $sumaMaterialesUsados->contains('cliente_id', $materialOcupadoFecha->cliente_id)) {
                $materialUsadoEncontrado = $sumaMaterialesUsados->first(function ($item) use ($materialOcupadoFecha) {
                    return $item->detalle_producto_id === $materialOcupadoFecha->detalle_producto_id && $item->cliente_id === $materialOcupadoFecha->cliente_id;
                });

                $materialOcupadoFecha->total_cantidad_utilizada = $materialUsadoEncontrado->suma_total;
            }
            $materialOcupadoFecha->id = $index + 1;
            return $materialOcupadoFecha;
        }); // ->filter(fn ($materialOcupadoFecha) => $materialOcupadoFecha->total_cantidad_utilizada > 0);


        return response()->json(compact('results'));
    }

    /******************
     * Material stock
     ******************/
    public function obtenerResumenMaterialSeguimientoStockUsado(Request $request)
    {
        $request->validate([
            'subtarea_id' => 'required|numeric|integer',
            'empleado_id' => 'required|numeric|integer',
        ]);

        return $this->consultarMaterialStockUtilizado($request['subtarea_id'], $request['empleado_id']);
    }

    public function obtenerHistorialMaterialStockUsadoPorFecha(Request $request)
    {
        $request->validate([
            'subtarea_id' => 'required|numeric|integer',
            'empleado_id' => 'required|numeric|integer',
            'fecha' => 'required|string',
        ]);

        return $this->consultarMaterialStockUtilizado($request['subtarea_id'], $request['empleado_id'], $request['fecha']);
    }

    public function consultarMaterialStockUtilizado($idSubtarea, $idEmpleado, $fecha = null)
    {
        $servicio = new TransaccionBodegaEgresoService();

        $fecha_convertida = $fecha ? Carbon::createFromFormat('d-m-Y', $fecha)->format('Y-m-d') : null;
        $idTarea = Subtarea::find($idSubtarea)->tarea_id;

        $materialesOcupadosFecha =  DB::table('seguimientos_materiales_stock as sms')->select('cantidad_utilizada', 'met.detalle_producto_id', 'met.cliente_id', 'met.empleado_id', 'met.despachado', 'met.cantidad_stock as stock_actual', 'met.devuelto', 'empresas.razon_social as cliente', 'dp.serial', 'unidades_medidas.simbolo as medida', 'clientes.id as cliente_id', 'dp.descripcion as detalle_producto')
            ->join('materiales_empleados as met', function ($query) use ($idEmpleado) {
                $query->on('met.detalle_producto_id', 'sms.detalle_producto_id')
                    ->on('met.cliente_id', '=', 'sms.cliente_id')
                    ->where('met.empleado_id', $idEmpleado);
            })
            ->join('detalles_productos as dp', 'sms.detalle_producto_id', '=', 'dp.id')
            ->join('clientes', 'sms.cliente_id', '=', 'clientes.id')
            ->join('empresas', 'clientes.empresa_id', '=', 'empresas.id')
            ->join('productos', 'dp.producto_id', '=', 'productos.id')
            ->join('unidades_medidas', 'productos.unidad_medida_id', 'unidades_medidas.id')
            ->when($fecha_convertida, function ($query, $fecha_convertida) {
                return $query->whereDate('sms.created_at', $fecha_convertida);
            })
            ->where('sms.empleado_id', $idEmpleado)
            ->where('sms.subtarea_id', $idSubtarea)
            ->get();
        // ->whereDate('sms.created_at', $fecha_convertida)

        $materialesOcupadosFechaSinCliente =  DB::table('seguimientos_materiales_stock as sms')->select('cantidad_utilizada', 'met.detalle_producto_id', 'met.cliente_id', 'met.empleado_id', 'met.despachado', 'met.cantidad_stock as stock_actual', 'met.devuelto', 'dp.serial', 'unidades_medidas.simbolo as medida', 'clientes.id as cliente_id', 'dp.descripcion as detalle_producto')
            ->join('materiales_empleados as met', function ($query) use ($idEmpleado) {
                $query->on('met.detalle_producto_id', 'sms.detalle_producto_id')
                    ->whereNull('met.cliente_id')
                    ->whereNull('sms.cliente_id')
                    ->where('met.empleado_id', $idEmpleado);
            })
            ->join('detalles_productos as dp', 'sms.detalle_producto_id', '=', 'dp.id')
            ->leftJoin('clientes', 'sms.cliente_id', '=', 'clientes.id')
            ->leftJoin('empresas', 'clientes.empresa_id', '=', 'empresas.id')
            ->join('productos', 'dp.producto_id', '=', 'productos.id')
            ->join('unidades_medidas', 'productos.unidad_medida_id', 'unidades_medidas.id')
            ->when($fecha_convertida, function ($query, $fecha_convertida) {
                return $query->whereDate('sms.created_at', $fecha_convertida);
            })
            ->where('sms.empleado_id', $idEmpleado)
            ->where('sms.subtarea_id', $idSubtarea)
            ->get();
        // ->whereDate('sms.created_at', $fecha_convertida)

        $results = $materialesOcupadosFecha->merge($materialesOcupadosFechaSinCliente);

        $sumaMaterialesUsados = $servicio->obtenerSumaMaterialStockUsadoHistorial($idSubtarea, $idEmpleado);

        $results = $results->map(function ($materialOcupadoFecha, $index) use ($sumaMaterialesUsados) {
            if ($sumaMaterialesUsados->contains('detalle_producto_id', $materialOcupadoFecha->detalle_producto_id) && $sumaMaterialesUsados->contains('cliente_id', $materialOcupadoFecha->cliente_id)) {
                $materialUsadoEncontrado = $sumaMaterialesUsados->first(function ($item) use ($materialOcupadoFecha) {
                    return $item->detalle_producto_id === $materialOcupadoFecha->detalle_producto_id && $item->cliente_id === $materialOcupadoFecha->cliente_id;
                });

                $materialOcupadoFecha->total_cantidad_utilizada = intval($materialUsadoEncontrado->suma_total);
            }
            $materialOcupadoFecha->id = $index + 1;
            return $materialOcupadoFecha;
        }); // ->filter(fn ($materialOcupadoFecha) => $materialOcupadoFecha->total_cantidad_utilizada > 0);

        // $results = $results->filter(fn ($materialOcupadoFecha) => true $materialOcupadoFecha->total_cantidad_utilizada > 0);

        return response()->json(compact('results'));
    }

    public function obtenerFechasHistorialMaterialesUsados(Request $request, Subtarea $subtarea)
    {
        $results = DB::table('seguimientos_materiales_subtareas')
            ->select(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y') AS fecha"))
            ->where('subtarea_id', $subtarea->id)
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
            ->get();

        return response()->json(compact('results'));
    }

    public function obtenerFechasHistorialMaterialesStockUsados(Request $request, Subtarea $subtarea)
    {
        $results = DB::table('seguimientos_materiales_stock')
            ->select(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y') AS fecha"))
            ->where('subtarea_id', $subtarea->id)
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
            ->get();

        return response()->json(compact('results'));
    }

    public function actualizarCantidadUtilizadaHistorial(Request $request)
    {
        $modelo = $this->seguimientoService->actualizarSeguimientoCantidadUtilizadaMaterialEmpleadoTareaHistorial($request);
        return response()->json(compact('modelo'));
    }

    public function actualizarCantidadUtilizadaHistorialStock(Request $request)
    {
        $modelo = $this->seguimientoService->actualizarSeguimientoCantidadUtilizadaMaterialEmpleadoStockHistorial($request);
        return response()->json(compact('modelo'));
    }

    public function actualizarCantidadUtilizadaMaterialTarea(Request $request)
    {
        $modelo = $this->seguimientoService->actualizarSeguimientoCantidadUtilizadaMaterialEmpleadoTarea($request);
        return response()->json(compact('modelo'));
    }

    public function actualizarCantidadUtilizadaMaterialStock(Request $request)
    {
        $modelo = $this->seguimientoService->actualizarSeguimientoCantidadUtilizadaMaterialEmpleadoStock($request);
        return response()->json(compact('modelo'));
    }

    public function obtenerClientesMaterialesEmpleado(Request $request)
    {
        $results = DB::table('materiales_empleados')
            ->select('materiales_empleados.cliente_id', 'empresas.razon_social')
            ->where('empleado_id', request('empleado_id'))
            ->where('cantidad_stock', '>', 0)
            ->join('clientes', 'cliente_id', '=', 'clientes.id')
            ->join('empresas', 'clientes.empresa_id', '=', 'empresas.id')
            ->groupBy('cliente_id')
            ->get();

        /* $results->push([
            'cliente_id' => null,
            'razon_social' => 'SIN CLIENTE',
        ]);*/

        return response()->json(compact('results'));
    }

    public function obtenerClientesMaterialesTarea(Request $request)
    {
        $results = MaterialEmpleadoTarea::select('cliente_id', 'empresas.razon_social')
            ->where('empleado_id', request('empleado_id'))
            ->where('cantidad_stock', '>', 0)
            ->devolverFiltroTareaEtapaProyecto()
            ->join('clientes', 'cliente_id', '=', 'clientes.id')
            ->join('empresas', 'clientes.empresa_id', '=', 'empresas.id')
            ->groupBy('cliente_id');

        // $sql = $results->toSql();
        // Log::channel('testing')->info('Log', compact('sql'));
        $results = $results->get();

        /* $results->push([
            'cliente_id' => null,
            'razon_social' => 'SIN CLIENTE',
        ]);*/


        return response()->json(compact('results'));
    }
}
