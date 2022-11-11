<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransaccionBodegaRequest;
use App\Http\Resources\TransaccionBodegaResource;
use App\Models\SubtipoTransaccion;
use App\Models\TransaccionBodega;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class TransaccionBodegaIngresoController extends Controller
{
    private $entidad = 'Transacción';
    public function __construct()
    {
        $this->middleware('can:puede.ver.transacciones_ingresos')->only('index', 'show');
        $this->middleware('can:puede.crear.transacciones_ingresos')->only('store');
        $this->middleware('can:puede.editar.transacciones_ingresos')->only('update');
        $this->middleware('can:puede.eliminar.transacciones_ingresos')->only('destroy');
    }

    public function list()
    {
        $page = request('page');
        $offset = request('offset');
        $results = [];
        if (auth()->user()->hasRole(User::ROL_BODEGA)) {
            if ($page) {
                if ($offset) {
                    $results = TransaccionBodega::simplePaginate($offset);
                    Log::channel('testing')->info('Log', ['Resultados en el if del offset: ', $results]);
                } else {
                    $results = TransaccionBodega::simplePaginate();
                    Log::channel('testing')->info('Log', ['Resultados en el else del offset: ', $results]);
                }

                $results->appends(['offset' => $offset]);
            } else {
                $transacciones = TransaccionBodega::all();
                Log::channel('testing')->info('Log', ['Resultados en el else general antes de filtrar el tipo: ', $transacciones]);
                $results = $transacciones->filter(fn ($transaccion) => $transaccion->subtipo->tipoTransaccion->tipo === 'INGRESO');
                Log::channel('testing')->info('Log', ['Resultados en el else general: ', $results]);
            }
            // TransaccionBodegaResource::collection($results);
            // Log::channel('testing')->info('Log', ['Resultados línea 47: ', $results]);
            // Log::channel('testing')->info('Log', ['Resultados línea 48: ', $results->filter(fn($transaccion)=>$transaccion->subtipo->tipoTransaccion->tipo==='INGRESO')]);
            // $transacciones = TransaccionBodega::all();
            // $results = $results->filter(fn($transaccion)=>$transaccion->subtipo->tipoTransaccion->tipo==='INGRESO');
            Log::channel('testing')->info('Log', ['Resultados línea 50 filtrados: ', $results]);
        }
        return TransaccionBodegaResource::collection($results);
        // return $results;
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        // return response()->json(['results'=>$this->list()]);
        /* $transacciones = DB::select(
            "select * from transacciones_bodega tb where subtipo_id in(
                select id from subtipos_transacciones st where tipo_transaccion_id in(
                    select id from tipos_transacciones tt where tipo='INGRESO'
                )
            )"
        ); */
        /* $ids=[];
        $results = [];
        foreach($transacciones as $transaccion){
            array_push($ids, $transaccion->id);
        }
        $results = TransaccionBodega::whereIn('id', $ids)->get(); */

        /* 
        $users = DB::table('users')
            ->join('contacts', 'users.id', '=', 'contacts.user_id')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->select('users.*', 'contacts.phone', 'orders.price')
            ->get();
        */
        $page = $request['page'];
        $results = [];
        if (auth()->user()->hasRole(User::ROL_BODEGA)) {
            if ($page) {
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->join('subtipos_transacciones', 'subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'INGRESO')
                    ->simplePaginate($request['offset']);
                TransaccionBodegaResource::collection($results);
                $results->appends(['offset' => $request['offset']]);
            } else {
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->join('subtipos_transacciones', 'subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'INGRESO')
                    ->filter()->get();
                $results = TransaccionBodegaResource::collection($results);
            }
        }
        // $results = TransaccionBodegaResource::collection($transacciones);
        return response()->json(compact('results'));
        // return response()->json(['results'=>TransaccionBodegaResource::collection($results)]);
    }

    /**
     * Guardar
     */
    public function store(TransaccionBodegaRequest $request)
    {
        if (auth()->user()->hasRole([User::ROL_COORDINADOR, User::ROL_BODEGA, User::ROL_CONTABILIDAD])) {
            try {
                $datos = $request->validated();
                // Log::channel('testing')->info('Log', ['Datos validados', $datos]);
                DB::beginTransaction();
                $datos['subtipo_id'] = $request->safe()->only(['subtipo'])['subtipo'];
                $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
                $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
                $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
                if ($request->per_atiende) {
                    $datos['per_atiende_id'] = $request->safe()->only(['per_atiende'])['per_atiende'];
                }
                //datos de las relaciones muchos a muchos
                // $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
                $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];

                //Comprobar si hay tarea
                if ($request->tarea) {
                    $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];
                }
                if ($request->subtarea) {
                    $datos['subtarea_id'] = $request->safe()->only(['subtarea'])['subtarea'];
                }

                //Creacion de la transaccion
                $transaccion = TransaccionBodega::create($datos);


                //Guardar la autorizacion con su observacion
                // if ($request->observacion_aut) {
                //     $transaccion->autorizaciones()->attach($datos['autorizacion'], ['observacion' => $datos['observacion_aut']]);
                // } else {
                //     $transaccion->autorizaciones()->attach($datos['autorizacion_id']);
                // }

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
        $datos['subtipo_id'] = $request->safe()->only(['subtipo'])['subtipo'];
        $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
        $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
        $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
        if ($request->per_atiende) {
            $datos['per_atiende_id'] = $request->safe()->only(['per_atiende'])['per_atiende'];
        }
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


                //borrar los registros de la tabla intermedia para guardar los modificados
                $transaccion->detalles()->detach();

                //Guardar los productos seleccionados
                foreach ($request->listadoProductosSeleccionados as $listado) {
                    $transaccion->detalles()->attach($listado['id'], ['cantidad_inicial' => $listado['cantidades']]);
                }


                DB::commit();
                $modelo = new TransaccionBodegaResource($transaccion->refresh());
                $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            } catch (Exception $e) {
                DB::rollBack();
                Log::channel('testing')->info('Log', ['ERROR en el insert de la transaccion de ingreso', $e->getMessage()]);
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
}
