<?php

namespace App\Http\Controllers;

use App\Events\DevolucionActualizadaSolicitanteEvent;
use App\Events\DevolucionAutorizadaEvent;
use App\Events\DevolucionCreadaEvent;
use App\Http\Requests\DevolucionRequest;
use App\Http\Resources\DevolucionResource;
use App\Models\Autorizacion;
use App\Models\Condicion;
use App\Models\ConfiguracionGeneral;
use App\Models\DetalleDevolucionProducto;
use App\Models\Devolucion;
use App\Models\Empleado;
use App\Models\EstadoTransaccion;
use App\Models\Producto;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use PhpParser\Node\Stmt\Break_;
use PhpParser\Node\Stmt\TryCatch;
use Src\App\ArchivoService;
use Src\App\Bodega\DevolucionService;
use Src\Config\Autorizaciones;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class DevolucionController extends Controller
{
    private $entidad = 'Devolución';
    private $archivoService;
    private $servicio;
    public function __construct()
    {
        $this->archivoService = new ArchivoService();
        $this->servicio = new DevolucionService();
        $this->middleware('can:puede.ver.devoluciones')->only('index', 'show');
        $this->middleware('can:puede.crear.devoluciones')->only('store');
        $this->middleware('can:puede.editar.devoluciones')->only('update');
        $this->middleware('can:puede.eliminar.devoluciones')->only('destroy');
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        try {
            $page = $request['page'];
            $offset = $request['offset'];
            $estado = $request['estado'];
            $campos = explode(',', $request['page']);
            $results = [];

            $results = $this->servicio->listar($request);

            $results = DevolucionResource::collection($results);

            return response()->json(compact('results'));
        } catch (Exception $e) {
            throw ValidationException::withMessages(['error' => [$e->getMessage() . '. ' . $e->getLine()]]);
        }
    }

    /**
     * Guardar
     */
    public function store(DevolucionRequest $request)
    {
        Log::channel('testing')->info('Log', ['recibido en el store de devoluciones', $request->all()]);
        $url = '/devoluciones';
        try {
            DB::beginTransaction();
            // Adaptacion de foreign keys
            $datos = $request->validated();
            $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
            $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];
            $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
            $datos['canton_id'] = $request->safe()->only(['canton'])['canton'];
            $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
            $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
            $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
            $datos['stock_personal'] = $request->es_para_stock;

            // Respuesta
            $devolucion = Devolucion::create($datos);
            Log::channel('testing')->info('Log', ['devolucion creada', $devolucion]);
            $modelo = new DevolucionResource($devolucion);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            if ($request->misma_condicion) {
                foreach ($request->listadoProductos as $listado) {
                    $devolucion->detalles()->attach($listado['id'], ['cantidad' => $listado['cantidad'], 'condicion_id' => $request->condicion]);
                }
            } else {
                foreach ($request->listadoProductos as $listado) {
                    $condicion = Condicion::where('nombre', $listado['condiciones'])->first();
                    $devolucion->detalles()->attach(
                        $listado['id'],
                        [
                            'cantidad' => $listado['cantidad'],
                            'observacion' => array_key_exists('observacion', $listado) ?  $listado['observacion'] : null,
                            'condicion_id' => $condicion->id
                        ]
                    );
                }
            }


            DB::commit();
            $msg = 'Devolución N°' . $devolucion->id . ' ' . $devolucion->solicitante->nombres . ' ' . $devolucion->solicitante->apellidos . ' ha realizado una devolución en la sucursal ' . $devolucion->sucursal->lugar . ' . La autorización está ' . $devolucion->autorizacion->nombre;
            event(new DevolucionCreadaEvent($msg, $url, $devolucion, $devolucion->solicitante_id, $devolucion->per_autoriza_id, false));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR del catch', $e->getMessage(), $e->getLine()]);
            throw ValidationException::withMessages(['error' => [$e->getMessage() . '. ' . $e->getLine()]]);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Devolucion $devolucion)
    {
        $modelo = new DevolucionResource($devolucion);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(DevolucionRequest $request, Devolucion $devolucion)
    {
        $url = '/devoluciones';
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
        $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];
        $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
        $datos['canton_id'] = $request->safe()->only(['canton'])['canton'];
        $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
        $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];

        // Respuesta
        $devolucion->update($datos);

        //borrar los registros de la tabla intermedia para guardar los modificados
        $devolucion->detalles()->detach();

        //Guardar los productos seleccionados
        foreach ($request->listadoProductos as $listado) {
            $condicion = Condicion::where('nombre', $listado['condiciones'])->first();
            $devolucion->detalles()->attach($listado['id'], ['cantidad' => $listado['cantidad'], 'observacion' => $listado['observacion'], 'condicion_id' => $condicion->id]);
        }

        $modelo = new DevolucionResource($devolucion->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        if ($devolucion->pedido_automatico && $devolucion->autorizacion_id == Autorizaciones::APROBADO) $this->servicio->crearPedidoAutomatico($devolucion);

        $msg = $devolucion->autoriza->nombres . ' ' . $devolucion->autoriza->apellidos . ' ha actualizado tu devolución, el estado de Autorización es: ' . $devolucion->autorizacion->nombre;
        event(new DevolucionActualizadaSolicitanteEvent($msg, $url, $devolucion, $devolucion->per_autoriza_id, $devolucion->solicitante_id, true)); //Se usa para notificar al tecnico que se actualizó la devolucion

        if ($devolucion->autorizacion->nombre === Autorizacion::APROBADO) {
            $devolucion->latestNotificacion()->update(['leida' => true]);
            $msg = 'Hay una devolución recién autorizada en la sucursal ' . $devolucion->sucursal->lugar . ' pendiente de despacho';
            event(new DevolucionAutorizadaEvent($msg, User::ROL_BODEGA, $url, $devolucion, true));
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(Devolucion $devolucion)
    {
        $devolucion->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }


    /**
     * Consultar datos sin el método show.
     */
    public function showPreview(Devolucion $devolucion)
    {
        $modelo = new DevolucionResource($devolucion);

        return response()->json(compact('modelo'), 200);
    }

    /**
     * Anular una devolución
     */
    public function anular(Request $request, Devolucion $devolucion)
    {
        $request->validate(['motivo' => ['required', 'string']]);
        $devolucion->causa_anulacion = $request['motivo'];
        $devolucion->estado = Devolucion::ANULADA;
        $devolucion->estado_bodega = EstadoTransaccion::ANULADA;
        $devolucion->save();

        $modelo = new DevolucionResource($devolucion->refresh());

        return response()->json(compact('modelo'));
    }

    public function corregirDevolucion(Request $request, Devolucion $devolucion)
    {
        if (count($request->listadoProductos) > 0) {
            foreach ($request->listadoProductos as $listado) {
                // $devolucion->detalles()->updateExistingPivot($listado['id'], ['cantidad'=>$listado['cantidad']]);
                $detalle = DetalleDevolucionProducto::where('devolucion_id', $devolucion->id)->where('detalle_id', $listado['id'])->first();
                $detalle->cantidad = $listado['cantidad'];
                $detalle->save();
            }
        }
    }

    public function eliminarDetalleDevolucion(Request $request)
    {
        $detalle = DetalleDevolucionProducto::where('devolucion_id', $request->devolucion_id)->where('detalle_id', $request->detalle_id)->first();
        $detalle->delete();
        $mensaje = 'El item ha sido eliminado con éxito';
        return response()->json(compact('mensaje'));
    }

    public function imprimir(Devolucion $devolucion)
    {
        $configuracion  = ConfiguracionGeneral::first();
        $resource = new DevolucionResource($devolucion);
        $persona_solicitante = Empleado::find($devolucion->solicitante_id);
        $persona_autoriza = Empleado::find($devolucion->per_autoriza_id);
        try {
            $devolucion = $resource->resolve();
            $pdf = Pdf::loadView('devoluciones.devolucion', compact(['devolucion', 'configuracion', 'persona_solicitante', 'persona_autoriza']));
            $pdf->setPaper('A5', 'landscape');
            $pdf->render();
            $file = $pdf->output();

            return $file;

            //usar esto en caso de querer guardar los pdfs generados en el servidor backend

            // $filename = "pedido_".$resource->id."_".time().".pdf";
            // $ruta = storage_path().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'devoluciones'.DIRECTORY_SEPARATOR.$filename;
            // file_put_contents($ruta, $file); en caso de que se quiera guardar el documento en el backend
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
            $mensaje = $ex->getMessage() . '. ' . $ex->getLine();
            return response()->json(compact('mensaje'), 500);
        }
    }

    /**
     * Listar archivos
     */
    public function indexFiles(Request $request, Devolucion $devolucion)
    {
        try {
            $results = $this->archivoService->listarArchivos($devolucion);
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            return response()->json(compact('mensaje'), 500);
        }
        return response()->json(compact('results'));
    }

    /**
     * Guardar archivos
     */
    public function storeFiles(Request $request, Devolucion $devolucion)
    {
        try {
            $modelo  = $this->archivoService->guardarArchivo($devolucion, $request->file, RutasStorage::DEVOLUCIONES->value . $devolucion->id);
            $mensaje = 'Archivo subido correctamente';
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage() . '. ' . $ex->getLine();
            return response()->json(compact('mensaje'), 500);
        }
        return response()->json(compact('mensaje', 'modelo'), 200);
    }
}
