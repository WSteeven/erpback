<?php

namespace App\Http\Controllers;

use App\Events\Bodega\IngresoPorCompraEvent;
use App\Exports\TransaccionBodegaIngresoExport;
use App\Http\Requests\TransaccionBodegaRequest;
use App\Http\Resources\ClienteResource;
use App\Http\Resources\TransaccionBodegaResource;
use App\Models\Cliente;
use App\Models\Condicion;
use App\Models\ConfiguracionGeneral;
use App\Models\DetalleProducto;
use App\Models\DetalleProductoTransaccion;
use App\Models\Empleado;
use App\Models\EstadoTransaccion;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\TransaccionBodega;
use App\Models\Transferencia;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Src\App\TransaccionBodegaIngresoService;
use Src\Shared\Utils;
use Throwable;

class TransaccionBodegaIngresoController extends Controller
{
    private string $entidad = 'Transacción';
    private TransaccionBodegaIngresoService $servicio;

    public function __construct()
    {
        $this->servicio = new TransaccionBodegaIngresoService();
        $this->middleware('can:puede.ver.transacciones_ingresos')->only('index', 'show');
        $this->middleware('can:puede.crear.transacciones_ingresos')->only('store');
        $this->middleware('can:puede.editar.transacciones_ingresos')->only('update');
        $this->middleware('can:puede.eliminar.transacciones_ingresos')->only('destroy');
    }


    /**
     * Listar
     */
    public function index(Request $request)
    {
        $results = $this->servicio->listar(null, null, $request->paginate,
            $request->search);
        return TransaccionBodegaResource::collection($results);
        // return response()->json(compact('results'));
    }

    /**
     * Guardar
     * @throws Throwable
     */
    public function store(TransaccionBodegaRequest $request)
    {
        try {
            if (auth()->user()->hasRole([User::ROL_COORDINADOR, User::ROL_BODEGA, User::ROL_BODEGA_TELCONET,  User::ROL_CONTABILIDAD])) {
                $datos = $request->validated();
                DB::beginTransaction();
                //                if ($request->transferencia) $datos['transferencia_id'] = $request->safe()->only(['transferencia'])['transferencia'];
                //                $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
                //                $datos['devolucion_id'] = $request->safe()->only(['devolucion'])['devolucion'];
                //                $datos['motivo_id'] = $request->safe()->only(['motivo'])['motivo'];
                //                $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
                //                $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
                //                $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
                //                $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
                //                if ($request->per_atiende) $datos['per_atiende_id'] = $request->safe()->only(['per_atiende'])['per_atiende'];
                //                $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];
                //                $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea']; //Comprobar si hay tarea

                // Crear la transaccion
                $transaccion = TransaccionBodega::create($datos);


                if ($request->ingreso_masivo) {
                    //Guardar los productos seleccionados en el detalle
                    foreach ($request->listadoProductosTransaccion as $listado) {
                        $producto = Producto::where('nombre', $listado['producto'])->first();
                        $detalle = DetalleProducto::where('producto_id', $producto->id)->where('descripcion', $listado['descripcion'])->first();
                        if ($listado['serial'] != null) $detalle = DetalleProducto::where('producto_id', $producto->id)->where('descripcion', $listado['descripcion'])->where('serial', $listado['serial'])->first();
                        TransaccionBodega::activarDetalle($detalle); //Aquí se activa el ítem del detalle
                        $item_inventario = Inventario::where('detalle_id', $detalle->id)->where('condicion_id', $request->condicion)->where('sucursal_id', $request->sucursal)->where('cliente_id', $request->cliente)->first();
                        if (!$item_inventario) {
                            $fila = Inventario::estructurarItem($detalle->id, $request->sucursal, $request->cliente, $request->condicion, $listado['cantidad']);
                            $item_inventario = Inventario::create($fila);
                        } else {
                            $item_inventario->update(['cantidad' => $item_inventario->cantidad + $listado['cantidad']]);
                        }
                        $transaccion->items()->attach($item_inventario->id, ['cantidad_inicial' => $listado['cantidad'],]);

                        //cuando se produce una devolucion de tarea se resta el material del stock de tarea del empleado
                        if ($transaccion->devolucion_id) {
                            $this->servicio->actualizarDevolucion($transaccion, $detalle, $listado['cantidad']);
                            $this->servicio->descontarMaterialesAsignados($listado, $transaccion, $detalle);
                        }
                    }
                } else {
                    foreach ($request->listadoProductosTransaccion as $listado) {
                        // Log::channel('testing')->info('Log', ['item del listado para ingresar cuando no es ingreso masivo', $listado]);
                        $condicion = Condicion::where('nombre', $listado['condiciones'])->first();
                        $producto = Producto::where('nombre', $listado['producto'])->first();
                        $transaccion->transferencia_id ? $detalle = DetalleProducto::find($listado['detalle_id']) : $detalle = DetalleProducto::where('producto_id', $producto->id)->where('descripcion', $listado['descripcion'])->first();
                        if ($listado['serial'] != null) $detalle = DetalleProducto::where('producto_id', $producto->id)->where('descripcion', $listado['descripcion'])->where('serial', $listado['serial'])->first();
                        TransaccionBodega::activarDetalle($detalle); //Aquí se activa el ítem del detalle
                        // $detalle = DetalleProducto::where('producto_id', $producto->id)->where('descripcion', $listado['descripcion'])->first();
                        // $itemInventario = Inventario::where('detalle_id', $detalle->id)->where('condicion_id', $listado['condiciones'])->where('cliente_id', $transaccion->cliente_id)->where('sucursal_id', $transaccion->sucursal_id)->first();
                        $item_inventario = Inventario::where('detalle_id', $detalle->id)->where('condicion_id', $condicion->id)->where('cliente_id', $transaccion->cliente_id)->where('sucursal_id', $transaccion->sucursal_id)->first();
                        if ($item_inventario) {
                            $item_inventario->cantidad = $item_inventario->cantidad + $listado['cantidad'];
                            $item_inventario->save();
                        } else {
                            $fila = Inventario::estructurarItem($detalle->id, $transaccion->sucursal_id, $transaccion->cliente_id, $condicion->id, $listado['cantidad']);
                            $item_inventario = Inventario::create($fila);
                        }
                        $transaccion->items()->attach($item_inventario->id, ['cantidad_inicial' => $listado['cantidad']]);

                        if ($transaccion->devolucion_id) {
                            $this->servicio->actualizarDevolucion($transaccion, $detalle, $listado['cantidad']);
                            $this->servicio->descontarMaterialesAsignados($listado, $transaccion, $detalle);
                        }
                    }
                }


                //se entiende que si hay un ingreso por transferencia es porque la transferencia llegó a su destino,
                // entonces procedemos a actualizar la transferencia
                if ($transaccion->transferencia_id) {
                    if (TransaccionBodega::verificarTransferenciaEnEgreso($transaccion->id, $transaccion->transferencia_id)) {
                        $transferencia = Transferencia::find($transaccion->transferencia_id);
                        $transferencia->estado = Transferencia::COMPLETADO;
                        $transferencia->recibida = true;
                        $transferencia->save();
                    } else {
                        throw new Exception('Primero debes realizar el EGRESO POR TRANSFERENCIA ENTRE BODEGAS en la bodega de origen');
                    }
                }

                DB::commit(); //Se registra la transaccion y sus detalles exitosamente
                if ($transaccion->motivo_id == 1) {
                    //en caso de que sea ingreso por COMPRA A PROVEEDOR se notifica a contabilidad
                    event(new IngresoPorCompraEvent($transaccion, User::ROL_CONTABILIDAD));
                }

                $modelo = new TransaccionBodegaResource($transaccion);
                $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

                return response()->json(compact('mensaje', 'modelo'));
            } else throw new Exception('Este usuario no puede realizar ingreso de materiales');
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->error('Log', ['ERROR en el insert de la transaccion de ingreso', $e->getMessage(), $e->getLine()]);
            throw ValidationException::withMessages(['error' => [$e->getMessage()]]);
        }
    }

    /**
     * Consultar
     */
    public function show(TransaccionBodega $transaccion)
    {
        // Log::channel('testing')->info('Log', ['Transaccion en el show de ingreso', $transaccion]);
        $modelo = new TransaccionBodegaResource($transaccion);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     * @throws Throwable
     */
    public function update(TransaccionBodegaRequest $request, TransaccionBodega $transaccion)
    {
        throw new Exception(Utils::metodoNoDesarrollado());
        $datos = $request->validated();
        // $datos['tipo_id'] = $request->safe()->only(['tipo'])['tipo'];
        //        $datos['devolucion_id'] = $request->safe()->only(['devolucion'])['devolucion'];
        //        $datos['motivo_id'] = $request->safe()->only(['motivo'])['motivo'];
        //        $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
        //        $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
        //        $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
        //        if ($request->per_atiende) $datos['per_atiende_id'] = $request->safe()->only(['per_atiende'])['per_atiende'];
        //datos de las relaciones muchos a muchos
        //        $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
        //        $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];

        //Comprobar si hay tarea
        //        if ($request->tarea) {
        //            $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];
        //        }
        //Comprobar si hay subtarea
        //        if ($request->subtarea) {
        //            $datos['subtarea_id'] = $request->safe()->only(['subtarea'])['subtarea'];
        //        }

        if ($transaccion->solicitante->id === auth()->user()->empleado->id) {
            try {
                DB::beginTransaction();
                //Actualización de la transacción
                $transaccion->update($datos);


                //borrar los registros de la tabla intermedia para guardar los modificados
                $transaccion->detalles()->detach();

                //Guardar los productos seleccionados
                foreach ($request->listadoProductosTransaccion as $listado) {
                    $transaccion->detalles()->attach($listado['id'], ['cantidad_inicial' => $listado['cantidad']]);
                }


                DB::commit();
                $modelo = new TransaccionBodegaResource($transaccion->refresh());
                $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            } catch (Exception $e) {
                DB::rollBack();
                Log::channel('testing')->error('Log', ['ERROR en el insert de la transaccion de ingreso', $e->getMessage(), $e->getLine()]);
                return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro'], 422);
            }

            return response()->json(compact('mensaje', 'modelo'));
        } else {
            //Aqui pregunta si es coordinador o jefe inmediato o bodeguero... solo ellos pueden modificar los datos de las transacciones de los demas
        }

        $message = 'No tienes autorización para modificar esta solicitud';
        $errors = ['message' => $message];
        return response()->json(['errors' => $errors], 422);
    }

    /**
     * Eliminar
     */
    public function destroy(TransaccionBodega $transaccion)
    {
        $transaccion->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }


    /**
     * Anular una transacción de ingreso y revertir el stock del inventario
     * @throws Throwable
     */
    public function anular(TransaccionBodega $transaccion)
    {
        // Log::channel('testing')->info('Log', ['Estamos en el metodo de anular el ingreso']);
        $estado_anulado = EstadoTransaccion::where('nombre', EstadoTransaccion::ANULADA)->first();
        if ($transaccion->estado_id !== $estado_anulado->id) {
            try {
                DB::beginTransaction();
                $detalles = DetalleProductoTransaccion::where('transaccion_id', $transaccion->id)->get();
                foreach ($detalles as $detalle) {
                    $item_inventario = Inventario::find($detalle['inventario_id']);
                    $item_inventario->cantidad -= $detalle['cantidad_inicial'];
                    $item_inventario->save();
                    if ($transaccion->devolucion_id) $this->servicio->anularIngresoDevolucion($transaccion, $transaccion->devolucion_id, $item_inventario->detalle_id, $detalle['cantidad_inicial']);
                }

                $transaccion->estado_id = $estado_anulado->id;
                $transaccion->save();
                DB::commit();
                // verificar anular la transferencia
                if ($transaccion->transferencia_id > 0) $transaccion->transferencia()->update(['recibida' => false, 'estado' => Transferencia::TRANSITO]);

                $mensaje = 'Transacción anulada correctamente';
                $modelo = new TransaccionBodegaResource($transaccion->refresh());
                return response()->json(compact('modelo', 'mensaje'));
            } catch (Exception $e) {
                DB::rollBack();
                Log::channel('testing')->error('Log', ['ERROR al anular la transaccion de ingreso', $e->getMessage(), $e->getLine()]);
                throw ValidationException::withMessages(['error' => [$e->getMessage()]]);
                // return response()->json(['mensaje' => 'Ha ocurrido un error al anular la transacción'], 422);
            }
        } else {
            // Log::channel('testing')->info('Log', ['La transacción está anulada, ya no se anulará nuevamente']);
            $mensaje = 'La transacción ya está anulada, ya no se anulará nuevamente';
            $modelo = new TransaccionBodegaResource($transaccion->refresh());
            return response()->json(compact('modelo', 'mensaje'));
        }
    }


    /**
     * Consultar datos sin metodo show
     */
    public function showPreview(TransaccionBodega $transaccion)
    {
        //        $detalles = TransaccionBodega::listadoProductos($transaccion->id);

        $modelo = new TransaccionBodegaResource($transaccion);

        return response()->json(compact('modelo'));
    }

    /**
     * Imprimir
     * @throws ValidationException
     */
    public function imprimir(TransaccionBodega $transaccion)
    {
        $configuracion = ConfiguracionGeneral::first();
        $resource = new TransaccionBodegaResource($transaccion);
        $cliente = new ClienteResource(Cliente::find($transaccion->cliente_id));
        $persona_entrega = Empleado::find($transaccion->solicitante_id);
        $persona_atiende = Empleado::find($transaccion->per_atiende_id);
        try {
            // Log::channel('testing')->info('Log', ['ingreso a imprimir', ['transaccion' => $resource->resolve(), 'persona_entrega' => $persona_entrega, 'persona_atiende' => $persona_atiende, 'cliente' => $cliente]]);
            $transaccion = $resource->resolve();
            // Log::channel('testing')->info('Log', ['resource a imprimir', $transaccion]);
            $transaccion['listadoProductosTransaccion'] = TransaccionBodega::listadoProductos($transaccion['id']);
            // Log::channel('testing')->info('Log', ['resource a completo', $transaccion]);
            $pdf = Pdf::loadView('ingresos.ingreso', compact(['transaccion', 'persona_entrega', 'persona_atiende', 'cliente', 'configuracion']));
            $pdf->setPaper('A5', 'landscape');
            $pdf->render();
            //            $filename = 'ingreso_' . $transaccion['id'] . '_' . time() . '.pdf';
            //            $ruta = storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'ingresos' . DIRECTORY_SEPARATOR . $filename;
            // file_put_contents($ruta, $file); //en caso de que se quiera guardar el documento en el backend
            return $pdf->output();
        } catch (Exception $ex) {
            Log::channel('testing')->error('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($ex);
        }
    }

    /**
     * Reportes
     * @throws Exception
     */
    public function reportes(Request $request)
    {
        // Log::channel('testing')->info('Log', ['Recibido del front', $request->all()]);
        $configuracion = ConfiguracionGeneral::first();
        switch ($request->accion) {
            case 'excel':
                $results = $this->servicio->filtrarIngresoPorTipoFiltro($request);
                $registros = TransaccionBodega::obtenerDatosReporteIngresos($results);
                //imprimir el excel
                return Excel::download(new TransaccionBodegaIngresoExport(collect($registros)), 'reporte.xlsx');
            case 'pdf':
                try {
                    $results = $this->servicio->filtrarIngresoPorTipoFiltro($request);
                    $registros = TransaccionBodega::obtenerDatosReporteIngresos($results);
                    $reporte = $registros;
                    $peticion = $request->all();
                    $pdf = Pdf::loadView('bodega.reportes.ingresos_bodega', compact(['reporte', 'peticion', 'configuracion']));
                    $pdf->setPaper('A4', 'landscape');
                    $pdf->render();
                    return $pdf->output();
                } catch (Exception $ex) {
                    Log::channel('testing')->error('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
                    throw Utils::obtenerMensajeErrorLanzable($ex);
                }
                break;
            default:
                //cuando llega el consultar
                $results = $this->servicio->filtrarIngresoPorTipoFiltro($request);
        }


        $results = TransaccionBodegaResource::collection($results);
        return response()->json(compact('results'));
    }

    public function editarFechaCompra(Request $request, TransaccionBodega $transaccion)
    {
        $request->validate(['fecha_compra' => 'required|date_format:Y-m-d']);

        return DB::transaction(function () use ($request, $transaccion) {
            $transaccion->fecha_compra = $request->fecha_compra;
            $transaccion->save();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            return response()->json(compact('mensaje'));
        });
    }
}
