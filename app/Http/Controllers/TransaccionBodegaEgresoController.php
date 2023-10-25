<?php

namespace App\Http\Controllers;

// Dependencias

use App\Events\TransaccionEgresoEvent;
use App\Exports\TransaccionBodegaEgresoExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Src\Shared\Utils;
use Exception;

// Modelos
use App\Models\MaterialEmpleadoTarea;
use App\Models\DetalleProducto;
use App\Models\TipoTransaccion;
use App\Models\Inventario;
use App\Models\Empleado;
use App\Models\Motivo;
use App\Models\TransaccionBodega;
use App\Models\User;

// Logica
use App\Http\Resources\TransaccionBodegaResource;
use App\Http\Requests\TransaccionBodegaRequest;
use App\Http\Resources\ClienteResource;
use App\Models\Cliente;
use App\Models\Comprobante;
use App\Models\ConfiguracionGeneral;
use App\Models\DetalleProductoTransaccion;
use App\Models\EstadoTransaccion;
use App\Models\MaterialEmpleado;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\SeguimientoMaterialSubtarea;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Src\App\TransaccionBodegaEgresoService;
use Src\Config\Autorizaciones;
use Src\Config\ClientesCorporativos;

class TransaccionBodegaEgresoController extends Controller
{
    private $entidad = 'Transacción';
    private $servicio;

    public function __construct()
    {
        $this->servicio = new TransaccionBodegaEgresoService();
        $this->middleware('can:puede.ver.transacciones_egresos')->only('index', 'show');
        $this->middleware('can:puede.crear.transacciones_egresos')->only('store');
        $this->middleware('can:puede.editar.transacciones_egresos')->only('update');
        $this->middleware('can:puede.eliminar.transacciones_egresos')->only('destroy');
    }


    // Stock personal: solo materiales excepto bobinas
    public function obtenerMaterialesEmpleado(Request $request)
    {
        $empleado_id = $request['empleado_id'];
        $results = MaterialEmpleado::filter()->where('empleado_id', $empleado_id)->get();

        $results = collect($results)->map(function ($item, $index) {
            $detalle = DetalleProducto::find($item->detalle_producto_id);
            $producto = Producto::find($detalle->producto_id);

            return [
                'id' => $index + 1,
                'producto' => $producto->nombre,
                'detalle_producto' => $detalle->descripcion,
                'detalle_producto_id' => $item->detalle_producto_id,
                'categoria' => $detalle->producto->categoria->nombre,
                'stock_actual' => intval($item->cantidad_stock),
                'medida' => $producto->unidadMedida?->simbolo,
                'serial' => $detalle->serial,
            ];
        });


        return response()->json(compact('results'));
    }

    // Stock personal: materiales y bobinas material para tarea no borrar
    public function obtenerMaterialesEmpleadoTarea(Request $request)
    {
        $request->validate([
            'tarea_id' => 'required|numeric|integer',
            'empleado_id' => 'required|numeric|integer',
            'subtarea_id' => 'nullable|numeric|integer',
        ]);

        // $empleado_id = Auth::user()->empleado->id;
        // $results = MaterialEmpleadoTarea::filter()->where('empleado_id', $empleado_id)->get();

        $results = MaterialEmpleadoTarea::ignoreRequest(['subtarea_id'])->filter()->get();
        $materialesUtilizadosHoy = SeguimientoMaterialSubtarea::where('empleado_id', $request['empleado_id'])->where('subtarea_id', $request['subtarea_id'])->whereDate('created_at', Carbon::now()->format('Y-m-d'))->get();

        // Log::channel('testing')->info('Log', compact('materialesUtilizadosHoy'));

        $materialesTarea = collect($results)->map(function ($item, $index) use ($materialesUtilizadosHoy) {
            $detalle = DetalleProducto::find($item->detalle_producto_id);
            $producto = Producto::find($detalle->producto_id);

            return [
                'id' => $index + 1,
                'producto' => $producto->nombre,
                'detalle_producto' => $detalle->descripcion,
                'detalle_producto_id' => $item->detalle_producto_id,
                'categoria' => $detalle->producto->categoria->nombre,
                'stock_actual' => intval($item->cantidad_stock),
                'despachado' => intval($item->despachado),
                'devuelto' => intval($item->devuelto),
                'cantidad_utilizada' => $materialesUtilizadosHoy->first(fn ($material) => $material->detalle_producto_id == $item->detalle_producto_id)?->cantidad_utilizada,
                'medida' => $producto->unidadMedida?->simbolo,
                'serial' => $detalle->serial,
            ];
        });

        // Fusionar
        // $materialesTarea = $materialesUtilizadosHoy->

        if ($request['subtarea_id']) {
            $materialesUsados = $this->servicio->obtenerSumaMaterialTareaUsado($request['subtarea_id'], $request['empleado_id']);
            $results = $materialesTarea->map(function ($material) use ($materialesUsados) {
                if ($materialesUsados->contains('detalle_producto_id', $material['detalle_producto_id'])) {
                    $material['total_cantidad_utilizada'] = $materialesUsados->first(function ($item) use ($material) {
                        return $item->detalle_producto_id === $material['detalle_producto_id'];
                    })->suma_total;
                }
                return $material;
            });

            return response()->json(compact('results'));
        }

        $results = $materialesTarea;

        return response()->json(compact('results'));
    }

    public function materialesDespachadosSinBobinaRespaldo($id)
    {
        $results = $this->servicio->obtenerListadoMaterialesPorTareaSinBobina($id);
        return response()->json(compact('results'));
    }

    public function materialesDespachados($id)
    {
        $results = $this->servicio->obtenerListadoMaterialesPorTarea($id);
        return response()->json(compact('results'));
    }

    public function prueba($id)
    {
        $results = $this->servicio->obtenerTransaccionesPorTarea($id);
        $results = TransaccionBodega::listadoProductosTarea($results);
        $results = TransaccionBodegaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $estado = $request['estado'];
        $tipoTransaccion = TipoTransaccion::where('nombre', TipoTransaccion::EGRESO)->first();
        $motivos = Motivo::where('tipo_transaccion_id', $tipoTransaccion->id)->get('id');
        $results = [];
        if (auth()->user()->hasRole([User::ROL_BODEGA, User::ROL_ADMINISTRADOR, User::ROL_CONTABILIDAD])) { //si es bodeguero
            $results = TransaccionBodega::whereIn('motivo_id', $motivos)->orderBy('id', 'desc')->get();
        }
        if (auth()->user()->hasRole([User::ROL_BODEGA_TELCONET])) {
            $results = TransaccionBodega::whereIn('motivo_id', $motivos)->where('cliente_id', ClientesCorporativos::TELCONET)->orderBy('id', 'desc')->get();
        }
        $results = TransaccionBodegaResource::collection($results);
        return response()->json(compact('results'));
    }


    /**
     * Guardar
     */
    public function store(TransaccionBodegaRequest $request)
    {
        $url = '/gestionar-egresos';
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            // $datos['tipo_id'] = $request->safe()->only(['tipo'])['tipo'];
            if ($request->pedido) $datos['pedido_id'] = $request->safe()->only(['pedido'])['pedido'];
            if ($request->transferencia) $datos['transferencia_id'] = $request->safe()->only(['transferencia'])['transferencia'];
            $datos['motivo_id'] = $request->safe()->only(['motivo'])['motivo'];
            $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
            $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
            $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
            $datos['per_retira_id'] = $request->safe()->only(['per_retira'])['per_retira'];
            $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];
            $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
            $datos['responsable_id'] = $request->safe()->only(['responsable'])['responsable'];
            $datos['per_retira_id'] = $request->safe()->only(['per_retira'])['per_retira'];
            if ($request->subtarea) $datos['subtarea_id'] = $request->safe()->only(['subtarea'])['subtarea'];
            if ($request->per_atiende) $datos['per_atiende_id'] = $request->safe()->only(['per_atiende'])['per_atiende'];

            //datos de las relaciones muchos a muchos
            $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
            $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];


            //Creacion de la transaccion
            $transaccion = TransaccionBodega::create($datos); //aqui se ejecuta el observer!!

            //Guardar los productos seleccionados
            foreach ($request->listadoProductosTransaccion as $listado) {
                // $itemInventario = Inventario::where('detalle_id', $listado['detalle'])->first();
                $itemInventario = Inventario::find($listado['id']);
                $transaccion->items()->attach($itemInventario->id, ['cantidad_inicial' => $listado['cantidad']]);
                //Actualizamos el estado del item de inventario
                TransaccionBodega::desactivarDetalle($itemInventario->detalle_id);
                // Actualizamos la cantidad en inventario
                $itemInventario->cantidad -= $listado['cantidad'];
                $itemInventario->save();
            }

            //Si hay pedido, actualizamos su estado.
            if ($transaccion->pedido_id) {
                $pedido = Pedido::find($transaccion->pedido_id);
                $pedido->latestNotificacion()->update(['leida' => true]);
                TransaccionBodega::actualizarPedido($transaccion);
            }

            DB::commit(); //Se registra la transaccion y sus detalles exitosamente

            $modelo = new TransaccionBodegaResource($transaccion);

            //verificamos si es un egreso por transferencia, en ese caso habría responsable de los materiales pero no se crea comprobante,
            if (!$transaccion->transferencia_id) {
                //creamos el comprobante
                $transaccion->comprobante()->save(new Comprobante(['transaccion_id' => $transaccion->id]));
                //lanzar el evento de la notificación
                $msg = 'Se ha generado un despacho de materiales a tu nombre, con transacción N°' . $transaccion->id . ', solicitado por ' . $modelo->solicitante->nombres . ' ' . $modelo->solicitante->apellidos . '. Por favor verifica y firma el movimiento';
                event(new TransaccionEgresoEvent($msg, $url, $transaccion, false));
            }
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR en el insert de la transaccion de egreso', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . $e->getLine()], 422);
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
    public function update(TransaccionBodegaRequest $request, TransaccionBodega $transaccion)
    {
        $datos = $request->validated();
        !is_null($request->pedido) ?? $datos['pedido_id'] = $request->safe()->only(['pedido'])['pedido'];
        if ($request->transferencia) $datos['transferencia_id'] = $request->safe()->only(['transferencia'])['transferencia'];
        if ($request->motivo) $datos['motivo_id'] = $request->safe()->only(['motivo'])['motivo'];
        $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
        $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
        $datos['motivo_id'] = $request->safe()->only(['motivo'])['motivo'];
        $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
        if ($request->per_atiende) $datos['per_atiende_id'] = $request->safe()->only(['per_atiende'])['per_atiende'];

        $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
        $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];

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
            $mensaje =  'Autorización actualizada correctamente';
            return response()->json(compact('mensaje', 'modelo'));
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
                } catch (Exception $e) {
                    DB::rollBack();
                    return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro'], 422);
                }
            }

            $modelo = new TransaccionBodegaResource($transaccion->refresh());
            $mensaje = 'Estado actualizado correctamente';
            return response()->json(compact('mensaje', 'modelo'));
        }
        // }

        /* $message = 'No tienes autorización para modificar esta solicitud';
        $errors = ['message' => $message];
        return response()->json(['errors' => $errors], 422); */
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
     * Anular una transacción de egreso y revertir el stock del inventario
     */
    public function anular(TransaccionBodega $transaccion)
    {
        try {
            DB::beginTransaction();
            $estadoAnulado = EstadoTransaccion::where('nombre', EstadoTransaccion::ANULADA)->first();
            $detalles = DetalleProductoTransaccion::where('transaccion_id', $transaccion->id)->get();
            foreach ($detalles as $detalle) {
                $itemInventario = Inventario::find($detalle['inventario_id']);
                $itemInventario->cantidad += $detalle['cantidad_inicial'];
                $itemInventario->save();
                $detalleProducto = DetalleProducto::find($itemInventario->detalle_id);
                TransaccionBodega::activarDetalle($detalleProducto);
            }
            $transaccion->estado_id = $estadoAnulado->id;
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
            return response()->json(['mensaje' => 'Ha ocurrido un error al anular la transacción'], 422);
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

        return response()->json(compact('modelo'), 200);
    }

    /**
     * Imprimir
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
            $transaccion['listadoProductosTransaccion'] = TransaccionBodega::listadoProductos($transaccion['id']);;
            // Log::channel('testing')->info('Log', ['Elementos a imprimir', ['transaccion' => $resource->resolve(), 'per_retira' => $persona_retira->toArray(), 'per_entrega' => $persona_entrega->toArray(), 'cliente' => $cliente]]);
            $pdf = Pdf::loadView('egresos.egreso', compact(['transaccion', 'persona_entrega', 'persona_retira', 'cliente', 'configuracion']));
            $pdf->setPaper('A5', 'landscape');
            $pdf->render();
            $file = $pdf->output();
            $filename = 'egreso_' . $resource->id . '_' . time() . '.pdf';
            $ruta = storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'egresos' . DIRECTORY_SEPARATOR . $filename;
            // file_put_contents($ruta, $file); //en caso de que se quiera guardar el documento en el backend
            return $file;
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
        }
    }

    /**
     * Reportes
     */
    public function reportes(Request $request)
    {
        $configuracion = ConfiguracionGeneral::first();
        $results = [];
        $registros = [];
        switch ($request->accion) {
            case 'excel':
                $results = $this->servicio->filtrarEgresoPorTipoFiltro($request);
                $registros = TransaccionBodega::obtenerDatosReporteEgresos($results);

                return Excel::download(new TransaccionBodegaEgresoExport(collect($registros)), 'reporte.xlsx');
                break;
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
                }
                break;
            default:
                $results = $this->servicio->filtrarEgresoPorTipoFiltro($request);
                break;
        }

        $results = TransaccionBodegaResource::collection($results);
        return response()->json(compact('results'));
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
    public function filtrarComprobante(Request $request)
    {
        $datos = TransaccionBodega::with('comprobante')->where('responsable_id', auth()->user()->empleado->id)
            ->whereHas('comprobante', function ($q) {
                $q->where('estado', request('estado'));
            })->get();

        $results = TransaccionBodegaResource::collection($datos);
        return response()->json(compact('results'));
    }

    public function filtrarEgresos(Request $request)
    {
        if (auth()->user()->hasRole([User::ROL_BODEGA, User::ROL_CONTABILIDAD, User::ROL_COORDINADOR, User::ROL_GERENTE, User::ROL_JEFE_TECNICO])) {
            $datos = TransaccionBodega::whereHas('comprobante', function ($q) {
                $q->where('estado', request('estado'));
            })->get();
        }
        $results = TransaccionBodegaResource::collection($datos);
        return response()->json(compact('results'));
    }
}
