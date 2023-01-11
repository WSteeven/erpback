<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransaccionBodegaRequest;
use App\Http\Resources\TransaccionBodegaResource;
use App\Models\Inventario;
use App\Models\MovimientoProducto;
use App\Models\TransaccionBodega;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
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

        $page = $request['page'];
        $offset = $request['offset'];
        $estado = $request['estado'];
        $tipo = 'INGRESO';
        $results = [];
        if (auth()->user()->hasRole(User::ROL_BODEGA)) {
            if ($page) {
                $results = $this->servicio->filtrarTransaccionesIngresoBodegueroConPaginacion($tipo, $estado, $offset);
            } else {
                $results = $this->servicio->filtrarTransaccionesIngresoBodegueroSinPaginacion($tipo, $estado);
            }
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

                $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
                $datos['devolucion_id'] = $request->safe()->only(['devolucion'])['devolucion'];
                $datos['motivo_id'] = $request->safe()->only(['motivo'])['motivo'];
                $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
                $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
                $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
                $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
                !is_null($request->per_atiende) ?? $datos['per_atiende_id'] = $request->safe()->only(['per_atiende'])['per_atiende'];
                $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];
                $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea']; //Comprobar si hay tarea

                //Creacion de la transaccion
                Log::channel('testing')->info('Log', ['Datos antes de ingresar', $datos]);

                $transaccion = TransaccionBodega::create($datos);
                Log::channel('testing')->info('Log', ['Transaccion creada', $transaccion]);

                //Guardar la autorización con su observación
                if($request->obs_autorizacion){
                    $transaccion->autorizaciones()->attach($datos['autorizacion_id'], ['observacion'=>$datos['obs_autorizacion']]);
                }else{
                    $transaccion->autorizaciones()->attach($datos['autorizacion_id']);
                }
                //Guardar el estado con su observacion
                if ($request->obs_estado) {
                    $transaccion->estados()->attach($datos['estado_id'], ['observacion' => $datos['obs_estado']]);
                } else {
                    $transaccion->estados()->attach($datos['estado_id']);
                }

                if ($request->ingreso_masivo) {
                    //Guardar los productos seleccionados en el detalle 
                    foreach ($request->listadoProductosTransaccion as $listado) {
                        $transaccion->detalles()->attach(
                            $listado['id'],
                            [
                                'cantidad_inicial' => $listado['cantidad'],
                                // 'cantidad_final' => $listado['cantidad']
                            ]
                        );
                    }
                    //Llamamos a la funcion de insertar cada elemento en el inventario
                    Inventario::ingresoMasivo($transaccion, $request->condicion, $request->listadoProductosTransaccion);

                } else {
                    foreach ($request->listadoProductosTransaccion as $listado) {
                        $transaccion->detalles()->attach($listado['id'], ['cantidad_inicial' => $listado['cantidad']]);
                    }
                }

                DB::commit(); //Se registra la transaccion y sus detalles exitosamente

                $modelo = new TransaccionBodegaResource($transaccion);
                $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            } catch (Exception $e) {
                DB::rollBack();
                Log::channel('testing')->info('Log', ['ERROR en el insert de la transaccion de ingreso', $e->getMessage(), $e->getLine()]);
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
        // $datos['tipo_id'] = $request->safe()->only(['tipo'])['tipo'];
        $datos['devolucion_id'] = $request->safe()->only(['devolucion'])['devolucion'];
        $datos['motivo_id'] = $request->safe()->only(['motivo'])['motivo'];
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
                if ($request->obs_estado) {
                    $transaccion->estados()->attach($datos['estado_id'], ['observacion' => $datos['obs_estado']]);
                } else {
                    $transaccion->estados()->attach($datos['estado_id']);
                }


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
        $estado = TransaccionBodega::ultimoEstado($transaccion->id);
        $detalles = TransaccionBodega::listadoProductos($transaccion->id);

        $modelo = new TransaccionBodegaResource($transaccion);

        return response()->json(compact('modelo'), 200);
    }
}
