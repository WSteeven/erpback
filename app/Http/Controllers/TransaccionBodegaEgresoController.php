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
use Src\Shared\Utils;

class TransaccionBodegaEgresoController extends Controller
{
    private $entidad = 'Transacción';
    public function __construct()
    {
        $this->middleware('can:puede.ver.transacciones_egresos')->only('index', 'show');
        $this->middleware('can:puede.crear.transacciones_egresos')->only('store');
        $this->middleware('can:puede.editar.transacciones_egresos')->only('update');
        $this->middleware('can:puede.eliminar.transacciones_egresos')->only('destroy');
    }

    public function list()
    {
        $idsSeleccionados = request('ids_seleccionados');

        if (request('subtarea_id')) {
            if ($idsSeleccionados) {
                $var = array_map('intval', explode(',', $idsSeleccionados));
                Log::channel('testing')->info('Log', ['arrays', $var]);
                return TransaccionBodegaResource::collection(TransaccionBodega::whereIn('id', $var)->where()->get()); // revisar
            }
            $transacciones = TransaccionBodega::ignoreRequest(['estado'])->filter()->get();
            // return TransaccionBodegaResource::collection($transacciones);
            return TransaccionBodegaResource::collection(TransaccionBodega::filtrarTransacciones($transacciones, 'COMPLETA'));
        }

        // Log::channel('testing')->info('Log', ['request en el metodo list', request('estado')]);
        if (auth()->user()->hasRole(User::ROL_COORDINADOR)) {
            $transacciones = TransaccionBodega::ignoreRequest(['estado'])->filter()->orWhere('per_autoriza_id', auth()->user()->empleado->id)->get();
            return TransaccionBodegaResource::collection(TransaccionBodega::filtrarTransacciones($transacciones, request('estado')));
        }
        if (auth()->user()->hasRole(User::ROL_BODEGA)) {
            $transacciones =  TransaccionBodega::ignoreRequest(['estado'])->filter()->get();
            $transacciones = $transacciones->filter(fn ($transaccion) => $transaccion->subtipo->tipoTransaccion->tipo === 'EGRESO');

            return TransaccionBodegaResource::collection(TransaccionBodega::filtrarTransacciones($transacciones, request('estado')));
        } else {
            $transacciones = TransaccionBodega::ignoreRequest(['estado'])->filter()->orWhere('solicitante_id', auth()->user()->empleado->id)->get();
            $transacciones = $transacciones->filter(fn ($transaccion) => $transaccion->subtipo->tipoTransaccion->tipo === 'EGRESO');

            return  TransaccionBodegaResource::collection(TransaccionBodega::filtrarTransacciones($transacciones, request('estado')));
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
        $results = [];
        if ($page) {
            if (auth()->user()->hasRole(User::ROL_COORDINADOR)) { //si es coordinador
                $results = TransaccionBodega::filtrarTransaccionesCoordinadorConPaginacion($estado, $offset);
                TransaccionBodegaResource::collection($results);
                $results->appends(['offset' => $request['offset']]);
            } elseif (auth()->user()->hasRole(User::ROL_BODEGA)) { //si es bodeguero
                $results = TransaccionBodega::filtrarTransaccionesBodegueroConPaginacion($estado, $offset);
                TransaccionBodegaResource::collection($results);
                $results->appends(['offset' => $request['offset']]);
            } else { //cualquier otro
                $results = TransaccionBodega::filtrarTransaccionesEmpleadoConPaginacion($estado, $offset);
                TransaccionBodegaResource::collection($results);
                $results->appends(['offset' => $request['offset']]);
            }
        } else { //si no hay paginacion
            if (auth()->user()->hasRole(User::ROL_COORDINADOR)) { //si es coordinador
                $results = TransaccionBodega::filtrarTransaccionesCoordinadorSinPaginacion($estado);
                TransaccionBodegaResource::collection($results);
                
            } elseif (auth()->user()->hasRole([User::ROL_BODEGA, User::ROL_ADMINISTRADOR])) { //si es bodeguero
                $results = TransaccionBodega::filtrarTransaccionesBodegueroSinPaginacion($estado);
                TransaccionBodegaResource::collection($results);
                
            } else { //cualquier otro
                $results = TransaccionBodega::filtrarTransaccionesEmpleadoSinPaginacion($estado);
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
            $datos['subtipo_id'] = $request->safe()->only(['subtipo'])['subtipo'];
            $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
            $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
            $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
            $datos['per_retira_id'] = $request->safe()->only(['per_retira'])['per_retira'];
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
            if ($request->observacion_aut) {
                $transaccion->autorizaciones()->attach($datos['autorizacion'], ['observacion' => $datos['observacion_aut']]);
            } else {
                $transaccion->autorizaciones()->attach($datos['autorizacion_id']);
            }

            //Guardar el estado con su observacion
            if ($request->observacion_est) {
                $transaccion->estados()->attach($datos['estado_id'], ['observacion' => $datos['observacion_est']]);
            } else {
                $transaccion->estados()->attach($datos['estado_id']);
            }
            //Guardar los productos seleccionados
            foreach ($request->listadoProductosSeleccionados as $listado) {
                $transaccion->detalles()->attach($listado['id'], ['cantidad_inicial' => $listado['cantidades']]);
            }

            DB::commit(); //Se registra la transaccion y sus detalles exitosamente

            $modelo = new TransaccionBodegaResource($transaccion);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR en el insert de la transaccion de ingreso', $e->getMessage()]);
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
        $datos['subtipo_id'] = $request->safe()->only(['subtipo'])['subtipo'];
        $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
        $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
        $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
        if ($request->subtarea_id) {
            $datos['subtarea_id'] = $request->safe()->only(['subtarea'])['subtarea'];
        }
        if ($request->per_atiende) {
            $datos['per_atiende_id'] = $request->safe()->only(['per_atiende'])['per_atiende'];
        }
        //datos de las relaciones muchos a muchos
        $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
        $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];

        if ($transaccion->solicitante->id === auth()->user()->empleado->id) {
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
        } else {
            //Aquí el coordinador o jefe inmediato autoriza la transaccion de sus subordinados y modifica los datos del listado
            if ($transaccion->per_autoriza_id === auth()->user()->empleado->id) {
                try {
                    DB::beginTransaction();
                    if ($request->observacion_aut) {
                        $transaccion->autorizaciones()->attach($datos['autorizacion'], ['observacion' => $datos['observacion_aut']]);
                    } else {
                        $transaccion->autorizaciones()->attach($datos['autorizacion_id']);
                    }
                    $transaccion->detalles()->detach();
                    foreach ($request->listadoProductosSeleccionados as $listado) { //Guarda los productos seleccionados
                        $transaccion->detalles()->attach($listado['id'], ['cantidad_inicial' => $listado['cantidades']]);
                    }
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                    return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar la autorización' . $e->getMessage()], 422);
                }

                $modelo = new TransaccionBodegaResource($transaccion->refresh());
                $mensaje =  'Autorización actualizada correctamente';
                return response()->json(compact('mensaje', 'modelo'));
            } else {
                if (auth()->user()->hasRole(User::ROL_BODEGA)) {
                    try {
                        DB::beginTransaction();
                        if ($request->observacion_est) {
                            $transaccion->estados()->attach($datos['estado'], ['observacion' => $datos['observacion_est']]);
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
        }

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
}
