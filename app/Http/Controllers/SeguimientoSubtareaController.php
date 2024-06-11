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

    // Eliminar este metodo
    public function obtenerHistorialMaterialTareaUsadoPorFechaOld(Request $request)
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

        $results = DB::table('seguimientos_materiales_subtareas as sms')
            ->select('dp.descripcion as detalle_producto', 'met.cantidad_stock as stock_actual', 'sms.cantidad_utilizada', 'met.despachado', 'met.devuelto', 'dp.id as detalle_producto_id', 'empresas.razon_social as cliente', 'dp.serial', 'unidades_medidas.simbolo as medida', 'clientes.id as cliente_id')
            ->join('detalles_productos as dp', 'sms.detalle_producto_id', '=', 'dp.id')
            ->join('materiales_empleados_tareas as met', function ($join) use ($idEmpleado, $idTarea) {
                $join->on('dp.id', '=', 'met.detalle_producto_id')
                    ->where('met.empleado_id', '=', $idEmpleado)
                    ->where('met.tarea_id', '=', $idTarea);
            })
            ->leftJoin('clientes', 'met.cliente_id', '=', 'clientes.id')
            ->LeftJoin('empresas', 'clientes.id', '=', 'empresas.id')
            ->leftJoin('productos', 'dp.producto_id', '=', 'productos.id')
            ->leftJoin('unidades_medidas', 'productos.unidad_medida_id', 'unidades_medidas.id')
            ->whereDate('sms.created_at', $fecha_convertida)
            ->where('sms.empleado_id', $idEmpleado)
            ->where('sms.subtarea_id', $idSubtarea)
            ->groupBy('detalle_producto_id')
            ->get();

        $servicio = new TransaccionBodegaEgresoService();
        $materialesUsados = $servicio->obtenerSumaMaterialTareaUsado($request['subtarea_id'], $request['empleado_id']);

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

    // Nuevo
    public function obtenerHistorialMaterialTareaUsadoPorFecha(Request $request)
    {
        $request->validate([
            'subtarea_id' => 'required|numeric|integer',
            'empleado_id' => 'required|numeric|integer',
            'fecha' => 'required|string',
        ]);

        $servicio = new TransaccionBodegaEgresoService();

        $fecha_convertida = Carbon::createFromFormat('d-m-Y', $request['fecha'])->format('Y-m-d');
        $idEmpleado = $request['empleado_id'];
        $idSubtarea = $request['subtarea_id'];
        $idTarea = Subtarea::find($idSubtarea)->tarea_id;

        /* $materialesOcupadosFecha = DB::table('seguimientos_materiales_subtareas as sms')
            ->select(detalle_producto_id'dp.descripcion as detalle_producto', 'met.cantidad_stock as stock_actual', 'sms.cantidad_utilizada', 'met.despachado', 'met.devuelto', 'dp.id as detalle_producto_id', 'empresas.razon_social as cliente', 'dp.serial', 'unidades_medidas.simbolo as medida', 'clientes.id as cliente_id')
            ->leftJoin('materiales_empleados_tareas as met', function ($join) use ($idEmpleado, $idTarea) {
                $join->on('sms.detalle_producto_id', '=', 'met.detalle_producto_id')
                    ->on('sms.cliente_id', '=', 'met.cliente_id')
                    ->where('met.empleado_id', '=', $idEmpleado)
                    ->where('met.tarea_id', '=', $idTarea);
            })
            ->join('detalles_productos as dp', 'sms.detalle_producto_id', '=', 'dp.id')
            ->leftJoin('clientes', 'sms.cliente_id', '=', 'clientes.id')
            ->leftJoin('empresas', 'clientes.empresa_id', '=', 'empresas.id')
            ->join('productos', 'dp.producto_id', '=', 'productos.id')
            ->join('unidades_medidas', 'productos.unidad_medida_id', 'unidades_medidas.id')
            ->whereDate('sms.created_at', $fecha_convertida)
            ->where('sms.empleado_id', $idEmpleado)
            ->where('sms.subtarea_id', $idSubtarea)
            ->get(); */
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
            ->whereDate('sms.created_at', $fecha_convertida)
            ->where('sms.empleado_id', $idEmpleado)
            ->where('sms.subtarea_id', $idSubtarea)
            ->get();

        $materialesOcupadosFechaSinCliente =  DB::table('seguimientos_materiales_subtareas as sms')->select('cantidad_utilizada', 'met.detalle_producto_id', 'met.cliente_id', 'met.empleado_id', 'met.despachado', 'met.cantidad_stock as stock_actual', 'met.devuelto', 'dp.serial', 'unidades_medidas.simbolo as medida', 'clientes.id as cliente_id', 'dp.descripcion as detalle_producto')
            ->join('materiales_empleados_tareas as met', function ($query) use ($idEmpleado, $idTarea) {
                $query->on('met.detalle_producto_id', 'sms.detalle_producto_id')
                    ->whereNull('met.cliente_id')
                    ->whereNull('sms.cliente_id')
                    ->where('met.tarea_id', '=', $idTarea)
                    ->where('met.empleado_id', $idEmpleado);
            })
            ->join('detalles_productos as dp', 'sms.detalle_producto_id', '=', 'dp.id')
            ->leftJoin('clientes', 'sms.cliente_id', '=', 'clientes.id')
            ->leftJoin('empresas', 'clientes.empresa_id', '=', 'empresas.id')
            ->join('productos', 'dp.producto_id', '=', 'productos.id')
            ->join('unidades_medidas', 'productos.unidad_medida_id', 'unidades_medidas.id')
            ->whereDate('sms.created_at', $fecha_convertida)
            ->where('sms.empleado_id', $idEmpleado)
            ->where('sms.subtarea_id', $idSubtarea)
            ->get();

        Log::channel('testing')->info('Log', compact('materialesOcupadosFecha', 'materialesOcupadosFechaSinCliente'));

        $results = $materialesOcupadosFecha->merge($materialesOcupadosFechaSinCliente);
        $sumaMaterialesUsados = $servicio->obtenerSumaMaterialTareaUsadoHistorial($request['subtarea_id'], $request['empleado_id']);

        $results = $results->map(function ($materialOcupadoFecha, $index) use ($sumaMaterialesUsados) {
            if ($sumaMaterialesUsados->contains('detalle_producto_id', $materialOcupadoFecha->detalle_producto_id) && $sumaMaterialesUsados->contains('cliente_id', $materialOcupadoFecha->cliente_id)) {
                $materialUsadoEncontrado = $sumaMaterialesUsados->first(function ($item) use ($materialOcupadoFecha) {
                    return $item->detalle_producto_id === $materialOcupadoFecha->detalle_producto_id && $item->cliente_id === $materialOcupadoFecha->cliente_id;
                });

                $materialOcupadoFecha->total_cantidad_utilizada = $materialUsadoEncontrado->suma_total;
            }
            $materialOcupadoFecha->id = $index + 1;
            return $materialOcupadoFecha;
        });


        return response()->json(compact('results'));
    }

    // Eliminar este metodo
    public function obtenerHistorialMaterialStockUsadoPorFechaOld(Request $request)
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

        $results = DB::table('seguimientos_materiales_stock as sms')
            ->select('dp.descripcion as detalle_producto', 'met.cantidad_stock as stock_actual', 'sms.cantidad_utilizada', 'met.despachado', 'met.devuelto', 'dp.id as detalle_producto_id', 'empresas.razon_social as cliente', 'dp.serial', 'unidades_medidas.simbolo as medida', 'clientes.id as cliente_id')
            ->join('detalles_productos as dp', 'sms.detalle_producto_id', '=', 'dp.id')
            ->join('materiales_empleados as met', function ($join) use ($idEmpleado, $idTarea) {
                $join->on('dp.id', '=', 'met.detalle_producto_id')
                    ->where('met.empleado_id', '=', $idEmpleado);
            })
            ->leftJoin('clientes', 'met.cliente_id', '=', 'clientes.id')
            ->LeftJoin('empresas', 'clientes.id', '=', 'empresas.id')
            ->leftJoin('productos', 'dp.producto_id', '=', 'productos.id')
            ->leftJoin('unidades_medidas', 'productos.unidad_medida_id', 'unidades_medidas.id')
            ->whereDate('sms.created_at', $fecha_convertida)
            ->where('sms.empleado_id', $idEmpleado)
            ->where('sms.subtarea_id', $idSubtarea)
            ->groupBy('detalle_producto_id')
            ->get();

        $servicio = new TransaccionBodegaEgresoService();
        $materialesUsados = $servicio->obtenerSumaMaterialStockUsado($request['subtarea_id'], $request['empleado_id'], $request['cliente_id']);
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

    // Nuevo
    public function obtenerHistorialMaterialStockUsadoPorFecha(Request $request)
    {
        $request->validate([
            'subtarea_id' => 'required|numeric|integer',
            'empleado_id' => 'required|numeric|integer',
            'fecha' => 'required|string',
        ]);

        $servicio = new TransaccionBodegaEgresoService();

        $fecha_convertida = Carbon::createFromFormat('d-m-Y', $request['fecha'])->format('Y-m-d');
        $idEmpleado = $request['empleado_id'];
        $idSubtarea = $request['subtarea_id'];
        $idTarea = Subtarea::find($idSubtarea)->tarea_id;
        // Log::channel('testing')->info('Log', compact('fecha_convertida'));

        /* $materialesOcupadosFecha = DB::table('seguimientos_materiales_stock as sms')
            ->select('dp.descripcion as detalle_producto', 'me.cantidad_stock as stock_actual', 'sms.cantidad_utilizada', 'me.despachado', 'me.devuelto', 'dp.id as detalle_producto_id', 'clientes.id as cliente_id', 'sms.created_at', 'empresas.razon_social as cliente', 'dp.serial', 'unidades_medidas.simbolo as medida')
            ->leftJoin('materiales_empleados as me', function ($join) use ($idEmpleado) {
                $join->on('sms.detalle_producto_id', '=', 'me.detalle_producto_id')
                    ->on('sms.cliente_id', '=', 'me.cliente_id')
                    ->where('me.empleado_id', '=', $idEmpleado);
            })
            ->join('detalles_productos as dp', 'sms.detalle_producto_id', '=', 'dp.id')
            ->join('clientes', 'sms.cliente_id', '=', 'clientes.id')
            ->join('empresas', 'clientes.empresa_id', '=', 'empresas.id')
            ->join('productos', 'dp.producto_id', '=', 'productos.id')
            ->join('unidades_medidas', 'productos.unidad_medida_id', 'unidades_medidas.id')
            ->whereDate('sms.created_at', $fecha_convertida)
            ->where('sms.empleado_id', $idEmpleado)
            ->where('sms.subtarea_id', $idSubtarea)
            ->get(); */

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
            ->whereDate('sms.created_at', $fecha_convertida)
            ->where('sms.empleado_id', $idEmpleado)
            ->where('sms.subtarea_id', $idSubtarea)
            ->get();

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
            ->whereDate('sms.created_at', $fecha_convertida)
            ->where('sms.empleado_id', $idEmpleado)
            ->where('sms.subtarea_id', $idSubtarea)
            ->get();

        Log::channel('testing')->info('Log', compact('materialesOcupadosFecha', 'materialesOcupadosFechaSinCliente'));

        $results = $materialesOcupadosFecha->merge($materialesOcupadosFechaSinCliente);

        $sumaMaterialesUsados = $servicio->obtenerSumaMaterialStockUsadoHistorial($request['subtarea_id'], $request['empleado_id']);

        $results = $materialesOcupadosFecha->map(function ($materialOcupadoFecha, $index) use ($sumaMaterialesUsados) {
            if ($sumaMaterialesUsados->contains('detalle_producto_id', $materialOcupadoFecha->detalle_producto_id) && $sumaMaterialesUsados->contains('cliente_id', $materialOcupadoFecha->cliente_id)) {
                $materialUsadoEncontrado = $sumaMaterialesUsados->first(function ($item) use ($materialOcupadoFecha) {
                    return $item->detalle_producto_id === $materialOcupadoFecha->detalle_producto_id && $item->cliente_id === $materialOcupadoFecha->cliente_id;
                });

                $materialOcupadoFecha->total_cantidad_utilizada = $materialUsadoEncontrado->suma_total;
            }
            $materialOcupadoFecha->id = $index + 1;
            return $materialOcupadoFecha;
        });

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
            ->join('clientes', 'cliente_id', '=', 'clientes.id')
            ->join('empresas', 'clientes.empresa_id', '=', 'empresas.id')
            ->groupBy('cliente_id')
            ->get();

        $results->push([
            'cliente_id' => null,
            'razon_social' => 'SIN CLIENTE',
        ]);

        return response()->json(compact('results'));
    }

    public function obtenerClientesMaterialesTarea(Request $request)
    {
        /* $empleado_id = request('empleado_id') ?? $empleado->id;
        Log::channel('testing')->info('Log', compact('empleado_id')); */

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

        $results->push([
            'cliente_id' => null,
            'razon_social' => 'SIN CLIENTE',
        ]);


        return response()->json(compact('results'));
    }

    /* function devolverFiltroTareaEtapaProyecto($query)
    {

        // $query = MaterialEmpleadoTarea::query();

        if(request('filtrar_por_tarea')) return $query->porTarea();
        if(request('filtrar_por_etapa')) return $query->porEtapa();
        if(request('filtrar_por_proyecto')) return $query->porProyecto();
    } */
}
