<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransaccionBodegaRequest;
use App\Http\Resources\TransaccionBodegaResource;
use App\Models\DetalleProducto;
use App\Models\Inventario;
use App\Models\Motivo;
use App\Models\Producto;
use App\Models\TipoTransaccion;
use App\Models\TransaccionBodega;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\App\TransaccionBodegaIngresoService;
use Src\Shared\Utils;

class TransaccionBodegaIngresoController extends Controller
{
    private $entidad = 'Transacción';
    private $servicio;
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
        $estado = $request['estado'];
        $tipoTransaccion = TipoTransaccion::where('nombre', TipoTransaccion::INGRESO)->first();
        $motivos = Motivo::where('tipo_transaccion_id', $tipoTransaccion->id)->get('id');
        $results = [];
        if (auth()->user()->hasRole(User::ROL_BODEGA)) {
            // $results = $this->servicio->filtrarTransaccionesIngresoBodegueroSinPaginacion($tipo, $estado);
            $results = TransaccionBodega::whereIn('motivo_id', $motivos)->get();
        }
        $results = TransaccionBodegaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(TransaccionBodegaRequest $request)
    {
        if (auth()->user()->hasRole([User::ROL_COORDINADOR, User::ROL_BODEGA, User::ROL_CONTABILIDAD])) {
            try {
                $datos = $request->validated();
                DB::beginTransaction();
                if ($request->transferencia) $datos['transferencia_id'] = $request->safe()->only(['transferencia'])['transferencia'];
                $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
                $datos['devolucion_id'] = $request->safe()->only(['devolucion'])['devolucion'];
                $datos['motivo_id'] = $request->safe()->only(['motivo'])['motivo'];
                $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
                $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
                $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
                $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
                if($request->per_atiende) $datos['per_atiende_id'] = $request->safe()->only(['per_atiende'])['per_atiende'];
                $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];
                $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea']; //Comprobar si hay tarea

                //Creacion de la transaccion
                Log::channel('testing')->info('Log', ['Datos recibidos del front', $request->all()]);
                Log::channel('testing')->info('Log', ['Datos antes de ingresar', $datos]);

                $transaccion = TransaccionBodega::create($datos);
                Log::channel('testing')->info('Log', ['Transaccion creada', $transaccion]);


                if ($request->ingreso_masivo) {
                    Log::channel('testing')->info('Log', ['ENTRO EN INGRESO MASIVO']);
                    //Guardar los productos seleccionados en el detalle
                    foreach ($request->listadoProductosTransaccion as $listado) {
                        Log::channel('testing')->info('Log', ['ITEM DEL LISTADO-FOREACH', $listado]);
                        Log::channel('testing')->info('Log', ['ITEM', $listado['id']]);
                        $itemInventario = Inventario::where('detalle_id', $listado['id'])
                        ->where('condicion_id', $request->condicion)
                        ->where('sucursal_id', $request->sucursal)
                        ->where('cliente_id', $request->cliente)
                        ->first();
                        Log::channel('testing')->info('Log', ['ITEMINVENTARIO', $itemInventario]);
                        if (!$itemInventario) {
                            Log::channel('testing')->info('Log', ['ESTOY EN EL IF-91', $itemInventario]);
                            $fila = Inventario::estructurarItem($listado['id'], $request->sucursal, $request->cliente, $request->condicion, $listado['cantidad']);
                            $itemInventario = Inventario::create($fila);
                        } else {
                            Log::channel('testing')->info('Log', ['ESTOY EN EL ELSE-95', $itemInventario]);
                            $itemInventario->update(['cantidad' => $itemInventario->cantidad + $listado['cantidad']]);
                        }
                        $transaccion->items()->attach(
                            $itemInventario->id,
                            [
                                'cantidad_inicial' => $listado['cantidad'],
                            ]
                        );
                    }
                    //Llamamos a la funcion de insertar cada elemento en el inventario
                    Inventario::ingresoMasivo($transaccion, $request->condicion, $request->listadoProductosTransaccion);
                } else {
                    Log::channel('testing')->info('Log', ['PASÓ DE LARGO']);
                    Log::channel('testing')->info('Log', ['REQUEST', $request->listadoProductosTransaccion]);
                    foreach ($request->listadoProductosTransaccion as $listado) {
                        $producto = Producto::where('nombre', $listado['producto'])->first();
                        $detalle = DetalleProducto::where('producto_id', $producto->id)->where('descripcion', $listado['descripcion'])->first();
                        $itemInventario = Inventario::where('detalle_id', $detalle->id)->where('condicion_id', $listado['condiciones'])->where('cliente_id', $transaccion->cliente_id)->first();
                        if ($itemInventario) {
                            Log::channel('testing')->info('Log', ['HAY UN ITEM COINCIDENTE EN EL INVENTARIO']);
                            $itemInventario->cantidad = $itemInventario->cantidad + $listado['cantidad'];
                            $itemInventario->save();
                            $transaccion->items()->attach($itemInventario->id, ['cantidad_inicial' => $listado['cantidad']]);
                        } else {
                            Log::channel('testing')->info('Log', ['NO HAY ITEM, SE VA A CREAR OTRO']);
                            $fila = Inventario::estructurarItem($detalle->id, $transaccion->sucursal_id, $transaccion->cliente_id, $listado['condiciones'], $listado['cantidad']);
                            $itemInventario = Inventario::create($fila);
                            $transaccion->items()->attach($itemInventario->id, ['cantidad_inicial' => $listado['cantidad']]);
                        }
                    }
                }

                DB::commit(); //Se registra la transaccion y sus detalles exitosamente

                $modelo = new TransaccionBodegaResource($transaccion);
                $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            } catch (Exception $e) {
                DB::rollBack();
                Log::channel('testing')->info('Log', ['ERROR en el insert de la transaccion de ingreso', $e->getMessage(), $e->getLine()]);
                return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . $e->getLine()], 422);
            }

            return response()->json(compact('mensaje', 'modelo'));
        } else return response()->json(compact('Este usuario no puede realizar ingreso de materiales'), 421);
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
     */
    public function update(TransaccionBodegaRequest $request, TransaccionBodega $transaccion)
    {
        $datos = $request->validated();
        // $datos['tipo_id'] = $request->safe()->only(['tipo'])['tipo'];
        $datos['devolucion_id'] = $request->safe()->only(['devolucion'])['devolucion'];
        $datos['motivo_id'] = $request->safe()->only(['motivo'])['motivo'];
        $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
        $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
        $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
        if ($request->per_atiende) $datos['per_atiende_id'] = $request->safe()->only(['per_atiende'])['per_atiende'];
        //datos de las relaciones muchos a muchos
        $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
        $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];

        //Comprobar si hay tarea
        if ($request->tarea) {
            $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];
        }
        //Comprobar si hay subtarea
        if ($request->subtarea) {
            $datos['subtarea_id'] = $request->safe()->only(['subtarea'])['subtarea'];
        }

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
                Log::channel('testing')->info('Log', ['ERROR en el insert de la transaccion de ingreso', $e->getMessage(), $e->getLine()]);
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
     * Consultar datos sin metodo show
     */
    public function showPreview(TransaccionBodega $transaccion)
    {
        $detalles = TransaccionBodega::listadoProductos($transaccion->id);

        $modelo = new TransaccionBodegaResource($transaccion);

        return response()->json(compact('modelo'), 200);
    }
    /**
     * Imprimir
     */
    public function imprimir(TransaccionBodega $transaccion)
    {
        $resource = new TransaccionBodegaResource($transaccion);
        Log::channel('testing')->info('Log', ['transaccion que se va a imprimir', $resource]);
        try {
            $pdf = Pdf::loadView('ingresos.ingreso', $resource->resolve());
            $pdf->setPaper('A5', 'landscape');
            $pdf->render();
            $file = $pdf->output();
            $filename = 'ingreso_' . $resource->id . '_' . time() . '.pdf';
            $ruta = storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'ingresos' . DIRECTORY_SEPARATOR . $filename;
            // file_put_contents($ruta, $file); //en caso de que se quiera guardar el documento en el backend
            return $file;
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
        }
    }
}
