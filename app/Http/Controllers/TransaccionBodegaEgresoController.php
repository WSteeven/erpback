<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransaccionBodegaRequest;
use App\Http\Resources\TransaccionBodegaResource;
use App\Models\Autorizacion;
use App\Models\EstadoTransaccion;
use App\Models\TransaccionBodega;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\App\TransaccionBodegaEgresoService;
use Src\Shared\Utils;

class TransaccionBodegaEgresoController extends Controller
{
    private $entidad = 'Transacción';
    public function __construct()
    {
        $this->servicio = new TransaccionBodegaEgresoService();
        $this->middleware('can:puede.ver.transacciones_egresos')->only('index', 'show');
        $this->middleware('can:puede.crear.transacciones_egresos')->only('store');
        $this->middleware('can:puede.editar.transacciones_egresos')->only('update');
        $this->middleware('can:puede.eliminar.transacciones_egresos')->only('destroy');
    }

    public function list()
    {
        /* $idsSeleccionados = request('ids_seleccionados');

        if (request('subtarea_id')) {
            if ($idsSeleccionados) {
                $var = array_map('intval', explode(',', $idsSeleccionados));
                Log::channel('testing')->info('Log', ['arrays', $var]);
                return TransaccionBodegaResource::collection(TransaccionBodega::whereIn('id', $var)->where()->get()); // revisar
            }
            $transacciones = TransaccionBodega::ignoreRequest(['estado'])->filter()->get();
            // return TransaccionBodegaResource::collection($transacciones);
            return TransaccionBodegaResource::collection(TransaccionBodega::filtrarTransaccionesEgreso($transacciones, 'COMPLETA'));
        } */

        // Log::channel('testing')->info('Log', ['request en el metodo list', request('estado')]);
        if (auth()->user()->hasRole(User::ROL_COORDINADOR)) {
            $transacciones = TransaccionBodega::ignoreRequest(['estado'])->filter()->orWhere('per_autoriza_id', auth()->user()->empleado->id)->get();
            return TransaccionBodegaResource::collection(TransaccionBodega::filtrarTransaccionesEgreso($transacciones, request('estado')));
        }

        if (auth()->user()->hasRole(User::ROL_BODEGA)) {
            $transacciones =  TransaccionBodega::ignoreRequest(['estado'])->filter()->get();
            $transacciones = $transacciones->filter(fn ($transaccion) => $transaccion->subtipo->tipoTransaccion->tipo === 'EGRESO');

            return TransaccionBodegaResource::collection(TransaccionBodega::filtrarTransaccionesEgreso($transacciones, request('estado')));
        } else {
            $transacciones = TransaccionBodega::ignoreRequest(['estado'])->filter()->orWhere('solicitante_id', auth()->user()->empleado->id)->get();
            $transacciones = $transacciones->filter(fn ($transaccion) => $transaccion->subtipo->tipoTransaccion->tipo === 'EGRESO');

            return  TransaccionBodegaResource::collection(TransaccionBodega::filtrarTransaccionesEgreso($transacciones, request('estado')));
        }
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $page = $request['page'];
        $offset = $request['offset'];
        $estado = $request['estado'];
        $tipo = 'EGRESO';
        $results = [];
        if ($page) {
            if (auth()->user()->hasRole(User::ROL_COORDINADOR)) { //si es coordinador
                $results = $this->servicio->filtrarTransaccionesEgresoCoordinadorConPaginacion($tipo, $estado, $offset);
                TransaccionBodegaResource::collection($results);
                $results->appends(['offset' => $request['offset']]);
            } elseif (auth()->user()->hasRole(User::ROL_BODEGA)) { //si es bodeguero
                $results = $this->servicio->filtrarTransaccionesEgresoBodegueroConPaginacion($tipo, $estado, $offset);
                TransaccionBodegaResource::collection($results);
                $results->appends(['offset' => $request['offset']]);
            } else { //cualquier otro
                $results = $this->servicio->filtrarTransaccionesEgresoEmpleadoConPaginacion($tipo, $estado, $offset);
                TransaccionBodegaResource::collection($results);
                $results->appends(['offset' => $request['offset']]);
            }
        } else { //si no hay paginacion
            if (auth()->user()->hasRole(User::ROL_COORDINADOR)) { //si es coordinador
                $results = $this->servicio->filtrarTransaccionesEgresoCoordinadorSinPaginacion($tipo, $estado);
                TransaccionBodegaResource::collection($results);
            } elseif (auth()->user()->hasRole([User::ROL_BODEGA, User::ROL_ADMINISTRADOR])) { //si es bodeguero
                $results = $this->servicio->filtrarTransaccionesEgresoBodegueroSinPaginacion($tipo, $estado);
                TransaccionBodegaResource::collection($results);
            } else { //cualquier otro
                $results = $this->servicio->filtrarTransaccionesEgresoEmpleadoSinPaginacion($tipo, $estado);
                TransaccionBodegaResource::collection($results);
            }
        }
        return response()->json(compact('results'));
    }


    /**
     * Guardar
     */
    public function store(TransaccionBodegaRequest $request)
    {
        Log::channel('testing')->info('Log', ['Datos recibidos', $request->all()]);
        try {
            $datos = $request->validated();
            Log::channel('testing')->info('Log', ['Datos validados', $datos]);
            DB::beginTransaction();
            // $datos['tipo_id'] = $request->safe()->only(['tipo'])['tipo'];
            !is_null($request->motivo) ?? $datos['motivo_id'] = $request->safe()->only(['motivo'])['motivo'];
            $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
            $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
            $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
            $datos['per_retira_id'] = $request->safe()->only(['per_retira'])['per_retira'];
            $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];
            $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
            if ($request->subtarea) {
                $datos['subtarea_id'] = $request->safe()->only(['subtarea'])['subtarea'];
            }
            if ($request->per_atiende) {
                $datos['per_atiende_id'] = $request->safe()->only(['per_atiende'])['per_atiende'];
            }
            //datos de las relaciones muchos a muchos
            $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
            $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];

            Log::channel('testing')->info('Log', ['Datos validados', $datos]);

            //Creacion de la transaccion
            $transaccion = TransaccionBodega::create($datos);


            //Guardar la autorizacion con su observacion
            if ($request->obs_autorizacion) {
                $transaccion->autorizaciones()->attach($datos['autorizacion'], ['observacion' => $datos['obs_autorizacion']]);
            } else {
                $transaccion->autorizaciones()->attach($datos['autorizacion_id']);
            }

            //Guardar el estado con su observacion
            if ($request->obs_estado) {
                $transaccion->estados()->attach($datos['estado_id'], ['observacion' => $datos['obs_estado']]);
            } else {
                $transaccion->estados()->attach($datos['estado_id']);
            }
            //Guardar los productos seleccionados
            foreach ($request->listadoProductosTransaccion as $listado) {
                $transaccion->detalles()->attach($listado['id'], ['cantidad_inicial' => $listado['cantidad']]);
            }

            DB::commit(); //Se registra la transaccion y sus detalles exitosamente

            $modelo = new TransaccionBodegaResource($transaccion);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR en el insert de la transaccion de egreso', $e->getMessage()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro'], 422);
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
        !is_null($request->motivo) ?? $datos['motivo_id'] = $request->safe()->only(['motivo'])['motivo'];
        $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
        $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
        $datos['motivo_id'] = $request->safe()->only(['motivo'])['motivo'];
        $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
        !is_null($request->subtarea_id)??$datos['subtarea_id'] = $request->safe()->only(['subtarea'])['subtarea'];
        !is_null($request->per_atiende)??$datos['per_atiende_id'] = $request->safe()->only(['per_atiende'])['per_atiende'];
        
        /* if ($request->subtarea_id) {
            $datos['subtarea_id'] = $request->safe()->only(['subtarea'])['subtarea'];
        }
        if ($request->per_atiende) {
            $datos['per_atiende_id'] = $request->safe()->only(['per_atiende'])['per_atiende'];
        } */
        //datos de las relaciones muchos a muchos
        $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
        $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];

        $transaccion->update($datos); //actualizar la transaccion

        /* if ($transaccion->solicitante->id === auth()->user()->empleado->id) {
            Log::channel('testing')->info('Log', ['entró en el if?', true]);
            try {
                DB::beginTransaction();
                $transaccion->update($datos); //Actualizacion de la transacción
                $transaccion->detalles()->detach(); //Borra los registros de la tabla intermedia para guardar los modificados
                foreach ($request->listadoProductosSeleccionados as $listado) { //Guarda los productos seleccionados
                    $transaccion->detalles()->attach($listado['id'], ['cantidad_inicial' => $listado['cantidades']]);
                }

                DB::commit();

                $modelo = new TransaccionBodegaResource($transaccion->refresh());
                $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro'], 422);
            }

            return response()->json(compact('mensaje', 'modelo'));
        } else { */
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
        $estado = TransaccionBodega::ultimoEstado($transaccion->id);
        $detalles = TransaccionBodega::listadoProductos($transaccion->id);
        $modelo = new TransaccionBodegaResource($transaccion);

        return response()->json(compact('modelo'), 200);
    }

    public function obtenerTransaccionPorTarea(int $tarea_id)
    {
        //$tarea_id = $request['tarea'];
        $modelo = TransaccionBodega::where('tarea_id', $tarea_id)->first();
        $modelo = new TransaccionBodegaResource($modelo);
        return response()->json(compact('modelo'));
    }
}
