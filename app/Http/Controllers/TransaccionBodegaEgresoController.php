<?php

namespace App\Http\Controllers;

// Dependencias

use App\Events\TransaccionEgresoEvent;
use Illuminate\Support\Facades\Auth;
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
use App\Models\Fibra;
use App\Models\Motivo;
use App\Models\Trabajo;
use App\Models\TransaccionBodega;
use App\Models\User;

// Logica
use App\Http\Resources\TransaccionBodegaResource;
use App\Http\Requests\TransaccionBodegaRequest;
use App\Models\Comprobante;
use App\Models\MaterialEmpleado;
use Src\App\TransaccionBodegaEgresoService;

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

    // Tarea: Obtener materiales designados a un empleado, para tarea
    /* public function obtenerMaterialesEmpleadoTareas(Request $request)
    {
        $request->validate([
            'subtarea_id' => 'required|numeric|integer',
            'empleado_id' => 'required|numeric|integer',
        ]);

        $tarea_id = Trabajo::find($request['subtarea_id'])->tarea_id;
        $empleado_id = $request['empleado_id'];

        $results = MaterialEmpleadoTarea::where('tarea_id', $tarea_id)->where('empleado_id', $empleado_id)->get(); // where('es_fibra', false)

        $results = collect($results)->map(fn ($items) => [
            'detalle_producto_id' => intval($items->detalle_producto_id),
            'stock_actual' => intval($items->cantidad_stock),
            'detalle_producto' => DetalleProducto::find($items->detalle_producto_id)->descripcion,
            'medida' => 'm',
        ]);

        return response()->json(compact('results'));
    } */

    // Stock personal: solo materiales excepto bobinas
    public function obtenerMaterialesEmpleado(Request $request)
    {
        $empleado_id = $request['empleado_id'];
        $results = MaterialEmpleado::filter()->where('empleado_id', $empleado_id)->get();

        $results = collect($results)->map(fn ($item, $index) => [
            'item' => $index + 1,
            'detalle_producto' => DetalleProducto::find($item->detalle_producto_id)->descripcion,
            'detalle_producto_id' => $item->detalle_producto_id,
            'stock_actual' => intval($item->cantidad_stock),
            'medida' => 'm',
        ]);

        return response()->json(compact('results'));
    }

    // Stock personal: materiales y bobinas material para tarea no borrar
     public function obtenerMaterialesEmpleadoTarea(Request $request)
    {
        $request->validate([
            'tarea_id' => 'required|numeric|integer',
            'empleado_id' => 'required|numeric|integer',
        ]);
        // $empleado_id = Auth::user()->empleado->id;
        // $results = MaterialEmpleadoTarea::filter()->where('empleado_id', $empleado_id)->get();
        $results = MaterialEmpleadoTarea::filter()->get();

        $results = collect($results)->map(fn ($item, $index) => [
            'item' => $index + 1,
            'detalle_producto' => DetalleProducto::find($item->detalle_producto_id)->descripcion,
            'detalle_producto_id' => $item->detalle_producto_id,
            'stock_actual' => intval($item->cantidad_stock),
            'medida' => 'm',
        ]);

        return response()->json(compact('results'));
    }


    // creo que se va
    // Tarea: Obtener bobinas designadas a un empleado, para tarea.
    /* public function obtenerBobinas(Request $request)
    {
        $request->validate([
            'subtarea_id' => 'required|numeric|integer',
        ]);

        $tarea_id = Trabajo::find($request['subtarea_id'])->tarea_id;
        $empleado_id = Auth::user()->empleado->id;

        $results = MaterialEmpleadoTarea::select('detalle_producto_id')->where('es_fibra', true)->where('tarea_id', $tarea_id)->where('empleado_id', $empleado_id)->get();

        $results = $results->map(fn ($item) => [
            'id' => $item->detalle_producto_id,
            'descripcion' => DetalleProducto::find($item->detalle_producto_id)->descripcion,
            'cantidad_hilos' => Fibra::where('detalle_id', $item->detalle_producto_id)->first()->hilo->nombre,
        ]);

        return response()->json(compact('results'));
    } */

    // #################################################################

    public function materialesDespachadosSinBobinaRespaldo($id)
    {
        $results = $this->servicio->obtenerListadoMaterialesPorTareaSinBobina($id);
        return response()->json(compact('results'));
    }

    //public function prueba2($id){
    public function materialesDespachados($id)
    {
        $results = $this->servicio->obtenerListadoMaterialesPorTarea($id);
        return response()->json(compact('results'));
    }

    public function prueba($id)
    {
        // Log::channel('testing')->info('Log', ['Dato recibido en prueba', $id]);
        $results = $this->servicio->obtenerTransaccionesPorTarea($id);
        // Log::channel('testing')->info('Log', ['Longitud es', count($results)]);
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
        if (auth()->user()->hasRole([User::ROL_BODEGA, User::ROL_ADMINISTRADOR])) { //si es bodeguero
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

            Log::channel('testing')->info('Log', ['Datos validados', $datos]);

            //Creacion de la transaccion
            $transaccion = TransaccionBodega::create($datos); //aqui se ejecuta el observer!!
            Log::channel('testing')->info('Log', ['Se créo la transaccion', $transaccion]);

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
            Log::channel('testing')->info('Log', ['Se pasó el foreach de guardar detalles', $transaccion]);

            //Si hay pedido, actualizamos su estado.
            if ($transaccion->pedido_id) {
                TransaccionBodega::actualizarPedido($transaccion);
            }
            Log::channel('testing')->info('Log', ['Se pasó la parte de actualizar pedidos', $transaccion]);

            DB::commit(); //Se registra la transaccion y sus detalles exitosamente

            //creamos el comprobante
            $transaccion->comprobante()->save(new Comprobante(['transaccion_id' => $transaccion->id]));
            Log::channel('testing')->info('Log', ['Pasamos crear el comprobante']);


            $modelo = new TransaccionBodegaResource($transaccion);
            Log::channel('testing')->info('Log', ['transaccion pasada por el resource', $modelo]);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            Log::channel('testing')->info('Log', ['Se guardaron los cambios y se prepara para enviar al front, estamos un paso antes de crear el comprobante']);

            //lanzar el evento de la notificación
            $msg = 'Se ha generado un despacho de materiales a tu nombre, con transacción N°' . $transaccion->id . ', solicitado por ' . $modelo->solicitante->nombres . ' ' . $modelo->solicitante->apellidos . '. Por favor verifica y firma el movimiento';
            event(new TransaccionEgresoEvent($msg, $url, $transaccion));
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
            Log::channel('testing')->info('Log', ['La persona que autoriza es igual al empleado actual?', true]);
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
                Log::channel('testing')->info('Log', ['El bodeguero realiza la actualizacion?', true, $request->all(), 'datos: ', $datos]);
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
        Log::channel('testing')->info('Log', ['Transacción a imprimir', $transaccion]);
        $resource = new TransaccionBodegaResource($transaccion);
        Log::channel('testing')->info('Log', ['Recurso a imprimir', $resource]);
        $persona_entrega = Empleado::find($transaccion->per_atiende_id);
        $persona_retira = Empleado::find($transaccion->responsable_id);
        try {
            $transaccion = $resource->resolve();

            Log::channel('testing')->info('Log', ['Elementos a imprimir', ['transaccion' => $resource->resolve(), 'per_retira' => $persona_retira->toArray(), 'per_entrega' => $persona_entrega->toArray()]]);
            // $pdf = Pdf::loadView('egresos.egreso', [$resource->resolve(), $persona_retira->toArray(), $persona_entrega->toArray()]);
            $pdf = Pdf::loadView('egresos.egreso', compact(['transaccion', 'persona_entrega', 'persona_retira']));
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
        // Log::channel('testing')->info('Log', ['[Metodo filtrar de transacciones egresos']);
        $datos = TransaccionBodega::with('comprobante')->where('responsable_id', auth()->user()->empleado->id)
            ->whereHas('comprobante', function ($q) {
                $q->where('estado', request('estado'));
            })->get();
        Log::channel('testing')->info('Log', ['egresos son:', $datos]);

        $results = TransaccionBodegaResource::collection($datos);
        return response()->json(compact('results'));
    }
}
