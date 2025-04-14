<?php

namespace App\Http\Controllers;

// Dependencias

use App\Events\Bodega\TransaccionEgresoEvent;
use App\Exports\Bodega\MaterialesDespachadosResponsableExport;
use App\Exports\TransaccionBodegaEgresoExport;
use App\Http\Requests\TransaccionBodegaRequest;
use App\Http\Resources\ClienteResource;
use App\Http\Resources\TransaccionBodegaResource;
use App\Models\Cliente;
use App\Models\Comprobante;
use App\Models\ConfiguracionGeneral;
use App\Models\DetalleProducto;
use App\Models\DetalleProductoTransaccion;
use App\Models\Empleado;
use App\Models\EstadoTransaccion;
use App\Models\Inventario;
use App\Models\MaterialEmpleado;
use App\Models\MaterialEmpleadoTarea;
use App\Models\Pedido;
use App\Models\TransaccionBodega;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Src\App\Tareas\ProductoEmpleadoService;
use Src\App\Tareas\ProductoTareaEmpleadoService;
use Src\App\TransaccionBodegaEgresoService;
use Src\Config\Autorizaciones;
use Src\Shared\Utils;
use Throwable;

// Modelos

// Logica

class TransaccionBodegaEgresoController extends Controller
{
    private string $entidad = 'Transacción';
    private TransaccionBodegaEgresoService $servicio;
    private ProductoEmpleadoService $productosEmpleadoService;
    private ProductoTareaEmpleadoService $productosTareaEmpleadoService;

    public function __construct()
    {
        $this->servicio = new TransaccionBodegaEgresoService();
        $this->productosEmpleadoService = new ProductoEmpleadoService();
        $this->productosTareaEmpleadoService = new ProductoTareaEmpleadoService();
        $this->middleware('can:puede.ver.transacciones_egresos')->only('index', 'show');
        $this->middleware('can:puede.crear.transacciones_egresos')->only('store');
        $this->middleware('can:puede.editar.transacciones_egresos')->only('update');
        $this->middleware('can:puede.eliminar.transacciones_egresos')->only('destroy');
    }

    // Stock personal: solo materiales excepto bobinas
    public function obtenerMaterialesEmpleado()
    {
        request()->validate(
            [
                'empleado_id' => 'required|numeric|integer|exists:empleados,id',
                'cliente_id' => 'nullable|numeric|integer|exists:clientes,id',
                'subtarea_id' => 'nullable|numeric|integer|exists:subtareas,id',
            ],
            [
                'empleado_id.required' => 'El campo empleado es obligatorio.',
                // 'cliente_id.required' => 'El campo cliente es obligatorio.',
            ]
        );

        $results = $this->productosEmpleadoService->obtenerProductos();
        return response()->json(compact('results'));
    }

    // Stock personal: materiales y bobinas material para tarea no borrar
    public function obtenerMaterialesEmpleadoTarea()
    {
        $results = $this->productosTareaEmpleadoService->obtenerProductos();
        return response()->json(compact('results'));
    }

    public function obtenerMaterialesEmpleadoConsolidadoOld(Request $request)
    {
        try {
            if (!$request->exists('cliente_id')) $request->merge(['cliente_id' => null]);

            $request->validate([
                'cliente_id' => 'nullable|sometimes|numeric|integer',
                'empleado_id' => 'required|numeric|integer',
            ]);

            if ($request['proyecto_id'] || $request['etapa_id'] || $request['tarea_id']) {
                $resultado2 = $this->productosTareaEmpleadoService->listarProductosConStock($request['proyecto_id'], $request['etapa_id'], $request['tarea_id']);
                $results = $this->mapear($resultado2);
                return response()->json(compact('results'));
            } else if ($request['stock_personal']) {
                $results = $this->productosEmpleadoService->obtenerProductos();
                $results = $this->mapearProductosEmpleado(collect($results));
                return response()->json(compact('results'));
            }

            $resultado1 = MaterialEmpleado::where('empleado_id', $request->empleado_id)->where('cliente_id', '=', $request->cliente_id)->where('cantidad_stock', '>', 0)->get();
            $resultado2 = MaterialEmpleadoTarea::where('empleado_id', $request->empleado_id)->where('cliente_id', '=', $request->cliente_id)->where('cantidad_stock', '>', 0)->get();
            $results = $resultado1->concat($resultado2);

            $results = $this->mapear($results);

            return response()->json(compact('results'));
        } catch (Throwable $th) {
            $mensaje = $th->getMessage() . '. ' . $th->getLine();
            return response()->json(compact('mensaje'));
        }
    }
    public function obtenerMaterialesEmpleadoConsolidado(Request $request)
    {
        try {
            if (!$request->exists('cliente_id')) $request->merge(['cliente_id' => null]);

            $request->validate([
                'cliente_id' => 'nullable|sometimes|numeric|integer',
                'empleado_id' => 'required|numeric|integer',
            ]);

            if ($request['proyecto_id'] || $request['etapa_id'] || $request['tarea_id']) { // Si el origen es de tarea
                $resultado2 = $this->productosTareaEmpleadoService->listarProductosConStock($request['proyecto_id'], $request['etapa_id'], $request['tarea_id']);
                $results = $this->mapear($resultado2);

                if($request['destino'] === 'STOCK') $results = $this->productosTareaEmpleadoService->filtrarHerramientasAccesoriosEquiposPropios($results);
                if($request['destino'] === 'TAREA') $results = $this->productosTareaEmpleadoService->filtrarMaterialesEquipos($results);

                return response()->json(compact('results'));
            } else if ($request['stock_personal']) { // Si el origen es de stock personal
                $results = $this->productosEmpleadoService->obtenerProductos();
                $results = $this->mapearProductosEmpleado(collect($results));

                if($request['destino'] === 'STOCK') $results = $this->productosTareaEmpleadoService->filtrarHerramientasAccesoriosEquiposPropios($results);
                if($request['destino'] === 'TAREA') $results = $this->productosTareaEmpleadoService->filtrarMaterialesEquipos($results);

                $results = $results->values();

                return response()->json(compact('results'));
            }

            $resultado1 = MaterialEmpleado::where('empleado_id', $request->empleado_id)->where('cliente_id', '=', $request->cliente_id)->where('cantidad_stock', '>', 0)->get();
            $resultado2 = MaterialEmpleadoTarea::where('empleado_id', $request->empleado_id)->where('cliente_id', '=', $request->cliente_id)->where('cantidad_stock', '>', 0)->get();
            $results = $resultado1->concat($resultado2);

            $results = $this->mapear($results);

            return response()->json(compact('results'));
        } catch (Throwable $th) {
            $mensaje = $th->getMessage() . '. ' . $th->getLine();
            return response()->json(compact('mensaje'));
        }
    }

    private function mapear($results)
    {
        return $results->map(function ($item) {
            return [
                'id' => $item->detalle_producto_id,
                'producto' => $item->detalle?->producto->nombre,
                'descripcion' => $item->detalle?->descripcion,
                'serial' => $item->detalle?->serial,
                'categoria' => $item->detalle?->producto->categoria->nombre,
                'modelo' => $item->detalle?->modelo->nombre,
                'cantidad' => $item->cantidad_stock,
                'cliente' => $item->cliente?->empresa?->razon_social,
            ];
        });
    }

    private function mapearProductosEmpleado($results)
    {
        return $results->map(function ($item) {
            $detalle = DetalleProducto::find($item['detalle_producto_id']);
            return [
                'id' => $item['detalle_producto_id'],
                'producto' => $detalle?->producto->nombre,
                'descripcion' => $detalle?->descripcion,
                'serial' => $detalle?->serial,
                'categoria' => $detalle?->producto->categoria->nombre,
                'modelo' => $detalle?->modelo->nombre,
                'cantidad' => $item['stock_actual'],
                'cliente' => Cliente::find($item['cliente_id'])?->empresa?->razon_social,
            ];
        });
    }

    /**
     * Listar
     * @throws Exception
     */
    public function index(Request $request)
    {
        $results = $this->servicio->listar($request, $request->paginate);
        return TransaccionBodegaResource::collection($results);
        //        return response()->json(compact('results'));
    }


    /**
     * Guardar
     * @throws ValidationException|Throwable
     */
    public function store(TransaccionBodegaRequest $request)
    {
        $url = '/gestionar-egresos';
        try {
            $datos = $request->validated();
            DB::beginTransaction();

            //Creacion de la transaccion
            $transaccion = TransaccionBodega::create($datos); //aqui se ejecuta el observer!!

            //Guardar los productos seleccionados
            foreach ($request->listadoProductosTransaccion as $listado) {
                // $itemInventario = Inventario::where('detalle_id', $listado['detalle'])->first();
                $item_inventario = Inventario::find($listado['id']);
                $transaccion->items()->attach($item_inventario->id, ['cantidad_inicial' => $listado['cantidad']]);
                //Actualizamos el estado del item de inventario
                TransaccionBodega::desactivarDetalle($item_inventario->detalle_id);
                // Actualizamos la cantidad en inventario
                $item_inventario->cantidad -= $listado['cantidad'];
                $item_inventario->save();
            }

            //Si hay pedido, actualizamos su estado.
            if ($transaccion->pedido_id) {
                $pedido = Pedido::find($transaccion->pedido_id);
                $pedido->latestNotificacion()->update(['leida' => true]);
                TransaccionBodega::actualizarPedido($transaccion);
            }

            DB::commit(); //Se registra la transaccion y sus detalles exitosamente

            $modelo = new TransaccionBodegaResource($transaccion);

            //verificamos si es un egreso por transferencia, en ese caso habría responsable de los materiales, pero no se crea comprobante,
            if (!$transaccion->transferencia_id) {
                $no_genera_comprobante = TransaccionBodega::verificarMotivosEgreso($transaccion->motivo_id);
                if (!$no_genera_comprobante) {
                    //creamos el comprobante
                    $transaccion->comprobante()->save(new Comprobante(['transaccion_id' => $transaccion->id]));
                    //lanzar el evento de la notificación
                    $msg = 'Se ha generado un despacho de materiales a tu nombre, con transacción N°' . $transaccion->id . ', solicitado por ' . Empleado::extraerNombresApellidos($transaccion->solicitante) . '. Por favor verifica y firma el movimiento';
                    event(new TransaccionEgresoEvent($msg, $url, $transaccion, false));
                }
            }
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR en el insert de la transaccion de egreso', $e->getMessage(), $e->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($e,  'Ha ocurrido un error al insertar el registro');
        }

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(TransaccionBodega $transaccion)
    {
        $modelo = new TransaccionBodegaResource($transaccion);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    /*
    public function update(TransaccionBodegaRequest $request, TransaccionBodega $transaccion)
    {
        $datos = $request->validated();
        //        !is_null($request->pedido) ?? $datos['pedido_id'] = $request->safe()->only(['pedido'])['pedido'];
        //        if ($request->transferencia) $datos['transferencia_id'] = $request->safe()->only(['transferencia'])['transferencia'];
        //        if ($request->motivo) $datos['motivo_id'] = $request->safe()->only(['motivo'])['motivo'];
        //        $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
        //        $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
        //        $datos['motivo_id'] = $request->safe()->only(['motivo'])['motivo'];
        //        $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
        //        if ($request->proyecto) $datos['proyecto_id'] = $request->safe()->only(['proyecto'])['proyecto'];
        //        if ($request->etapa) $datos['etapa_id'] = $request->safe()->only(['etapa'])['etapa'];
        //        if ($request->tarea) $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];
        //        if ($request->per_atiende) $datos['per_atiende_id'] = $request->safe()->only(['per_atiende'])['per_atiende'];

        //        $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
        //        $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];

        $transaccion->update($datos); //actualizar la transaccion

        //Aquí el coordinador o jefe inmediato autoriza la transaccion de sus subordinados y modifica los datos del listado
        if ($transaccion->per_autoriza_id === auth()->user()->empleado->id) {
            try {
                DB::beginTransaction();
                if ($request->obs_autorizacion) {
                    $transaccion->autorizaciones()->attach($datos['autorizacion'], ['observacion' => $datos['obs_autorizacion']]);
                } else {
                    $transaccion->autorizaciones()->attach($datos['autorizacion_id']);
                }
                $transaccion->detalles()->detach(); //borra el listado anterior
                foreach ($request->listadoProductosTransaccion as $listado) { //Guarda los productos seleccionados en un nuevo listado
                    $transaccion->detalles()->attach($listado['id'], ['cantidad_inicial' => $listado['cantidad']]);
                }
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar la autorización. ' . $e->getMessage()], 422);
            }

            $modelo = new TransaccionBodegaResource($transaccion->refresh());
            $mensaje = 'Autorización actualizada correctamente';
        } else {
            if (auth()->user()->hasRole(User::ROL_BODEGA)) {
                // Log::channel('testing')->info('Log', ['El bodeguero realiza la actualizacion?', true, $request->all(), 'datos: ', $datos]);
                try {
                    DB::beginTransaction();
                    if ($request->obs_estado) {
                        $transaccion->estados()->attach($datos['estado'], ['observacion' => $datos['obs_estado']]);
                    } else {
                        $transaccion->estados()->attach($datos['estado_id']);
                    }
                    DB::commit();
                } catch (Exception $ex) {
                    DB::rollBack();
                    //                    return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro'], 422);
                    throw Utils::obtenerMensajeErrorLanzable($ex);
                }
            }

            $modelo = new TransaccionBodegaResource($transaccion->refresh());
            $mensaje = 'Estado actualizado correctamente';
        }
        return response()->json(compact('mensaje', 'modelo'));
    }*/

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
     * Anular una transacción de egreso y revertir el stock del inventario
     * @throws ValidationException|Throwable
     */
    public function anular(TransaccionBodega $transaccion)
    {
        try {
            DB::beginTransaction();
            $estado_anulado = EstadoTransaccion::where('nombre', EstadoTransaccion::ANULADA)->first();
            $detalles = DetalleProductoTransaccion::where('transaccion_id', $transaccion->id)->get();
            foreach ($detalles as $detalle) {
                $item_inventario = Inventario::find($detalle['inventario_id']);
                $item_inventario->cantidad += $detalle['cantidad_inicial'];
                $item_inventario->save();
                $detalle_producto = DetalleProducto::find($item_inventario->detalle_id);
                TransaccionBodega::activarDetalle($detalle_producto);
                if ($transaccion->pedido_id) {
                    TransaccionBodega::restarDespachoPedido($transaccion->pedido_id, $item_inventario->detalle_id, $detalle['cantidad_inicial']);
                }
            }
            $transaccion->estado_id = $estado_anulado->id;
            $transaccion->autorizacion_id = Autorizaciones::CANCELADO;

            $transaccion->save();
            $transaccion->comprobante()->delete();
            $transaccion->latestNotificacion()->update(['leida' => true]);
            DB::commit();
            $mensaje = 'Transacción anulada correctamente';
            $modelo = new TransaccionBodegaResource($transaccion->refresh());
            return response()->json(compact('modelo', 'mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR al anular la transaccion de egreso', $e->getMessage(), $e->getLine()]);
            throw  Utils::obtenerMensajeErrorLanzable($e, 'Ha ocurrido un error al anular la transacción');
        }
    }

    /**
     * Consultar datos sin metodo show
     */
    public function showPreview(TransaccionBodega $transaccion)
    {
        $detalles = TransaccionBodega::listadoProductos($transaccion->id);
        $modelo = new TransaccionBodegaResource($transaccion);
        $modelo = $modelo->resolve();
        $modelo['listadoProductosTransaccion'] = $detalles;

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
        $persona_entrega = Empleado::find($transaccion->per_atiende_id);
        $persona_retira = Empleado::find($transaccion->responsable_id);
        try {
            $transaccion = $resource->resolve();
            $transaccion['listadoProductosTransaccion'] = TransaccionBodega::listadoProductos($transaccion['id']);
            // Log::channel('testing')->info('Log', ['Elementos a imprimir', ['transaccion' => $resource->resolve(), 'per_retira' => $persona_retira->toArray(), 'per_entrega' => $persona_entrega->toArray(), 'cliente' => $cliente]]);
            $pdf = Pdf::loadView('egresos.egreso', compact(['transaccion', 'persona_entrega', 'persona_retira', 'cliente', 'configuracion']));
            $pdf->setPaper('A5', 'landscape');
            $pdf->render();
            return $pdf->output();
            //            $filename = 'egreso_' . $resource->id . '_' . time() . '.pdf';
            //            $ruta = storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'egresos' . DIRECTORY_SEPARATOR . $filename;
            // file_put_contents($ruta, $file); //en caso de que se quiera guardar el documento en el backend
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($ex);
        }
    }

    /**
     * @throws ValidationException
     */
    public function imprimirActaEntregaRecepcion(TransaccionBodega $transaccion)
    {
        $configuracion = ConfiguracionGeneral::first();
        $resource = new TransaccionBodegaResource($transaccion);
        $cliente = new ClienteResource(Cliente::find($transaccion->cliente_id));
        $persona_entrega = Empleado::find($transaccion->per_atiende_id);
        $persona_retira = Empleado::find($transaccion->responsable_id);
        try {
            $transaccion = $resource->resolve();
            $transaccion['listadoProductosTransaccion'] = TransaccionBodega::listadoProductosArmamento($transaccion['id']);
            $pdf = Pdf::loadView('egresos.acta_entrega_recepcion', compact(['transaccion', 'persona_entrega', 'persona_retira', 'cliente', 'configuracion']));
            $pdf->setPaper('A5', 'landscape');
            $pdf->render();
            return $pdf->output();
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($ex);
        }
    }

    /**
     * Reportes
     * @throws ValidationException
     */
    public function reportes(Request $request)
    {
        $configuracion = ConfiguracionGeneral::first();
        $results = [];
        switch ($request->accion) {
            case 'excel':
                $results = $this->servicio->filtrarEgresoPorTipoFiltro($request);
                $registros = TransaccionBodega::obtenerDatosReporteEgresos($results);

                return Excel::download(new TransaccionBodegaEgresoExport(collect($registros)), 'reporte.xlsx');
            case 'pdf':
                try {
                    $results = $this->servicio->filtrarEgresoPorTipoFiltro($request);
                    $registros = TransaccionBodega::obtenerDatosReporteEgresos($results);
                    $reporte = $registros;
                    $peticion = $request->all();
                    $pdf = Pdf::loadView('bodega.reportes.egresos_bodega', compact(['reporte', 'peticion', 'configuracion']));
                    $pdf->setPaper('A4', 'landscape');
                    $pdf->render();
                    return $pdf->output();
                } catch (Exception $ex) {
                    Log::channel('testing')->info('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
                    throw Utils::obtenerMensajeErrorLanzable($ex);
                }
                break;
            default:
                $results = $this->servicio->filtrarEgresoPorTipoFiltro($request);
                break;
        }

        $results = TransaccionBodegaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * @throws ValidationException
     */
    public function reporteUniformesEpps(Request $request)
    {
        try {
            switch ($request->accion) {
                case 'excel':
                    $results = $this->servicio->filtrarEgresosResponsablePorCategoria($request);
                    $persona_entrega = Empleado::find($results[0]['per_atiende_id']);
                    $persona_responsable = Empleado::find($request->responsable);
                    $registros = TransaccionBodega::obtenerDatosReporteResponsable($results, $request->categorias);

                    return Excel::download(new MaterialesDespachadosResponsableExport(collect($registros), $persona_entrega, $persona_responsable), 'reporte_epps.xlsx');
                default:
                    throw ValidationException::withMessages(['error' => 'Método no implementado']);
            }
        } catch (Throwable | Exception $th) {
            throw Utils::obtenerMensajeErrorLanzable($th, 'reporteUniformesEpps');
        }
    }


    public function obtenerTransaccionPorTarea(int $tarea_id)
    {
        //$tarea_id = $request['tarea'];
        $modelo = TransaccionBodega::where('tarea_id', $tarea_id)->first();
        $modelo = new TransaccionBodegaResource($modelo);
        return response()->json(compact('modelo'));
    }

    /**
     * Esta función devuelve los egresos de un responsable, para que los pueda firmar y descargar siempre que sea necesario
     */
    public function showEgresos()
    {
        $results = TransaccionBodega::where('responsable_id', auth()->user()->empleado->id)->get();
        $results = TransaccionBodegaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Esta función filtra las transacciones segun el estado de su comprobante y las envía al front donde se ubican en sus respectivas pestañas
     */
    public function filtrarComprobante()
    {
        $datos = TransaccionBodega::with('comprobante')->where('responsable_id', auth()->user()->empleado->id)
            ->whereHas('comprobante', function ($q) {
                $q->where('estado', request('estado'));
            })->orderBy('id', 'desc')->get();

        $results = TransaccionBodegaResource::collection($datos);
        return response()->json(compact('results'));
    }

    public function filtrarEgresos()
    {
        $datos = [];
        if (auth()->user()->hasRole([User::ROL_BODEGA, User::ROL_CONTABILIDAD, User::ROL_COORDINADOR, User::ROL_GERENTE, User::ROL_JEFE_TECNICO, User::ROL_EMPLEADO])) {
            $datos = TransaccionBodega::whereHas('comprobante', function ($q) {
                $q->where('estado', request('estado'));
            })->get();
        }
        $results = TransaccionBodegaResource::collection($datos);
        return response()->json(compact('results'));
    }

    /**
     * @throws ValidationException
     */
    public function modificarItemEgreso(Request $request, TransaccionBodega $transaccion)
    {
        //         Log::channel('testing')->info('Log', ['¿modificarItemEgreso?', $request->all()]);
        try {
            switch ($request->tipo) {
                case 'PENDIENTE':
                    $this->servicio->modificarItemEgresoPendiente($request, $transaccion);
                    break;
                case 'PARCIAL':
                    $this->servicio->modificarItemEgresoParcial($request, $transaccion);
                    break;
            }


            $modelo = [];
        } catch (Throwable $th) {
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
        return response()->json(compact('modelo'));
    }
}
