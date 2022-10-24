<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransaccionBodegaRequest;
use App\Http\Resources\TransaccionBodegaResource;
use App\Models\SubtipoTransaccion;
use App\Models\TransaccionBodega;
use App\Models\User;
use Exception;
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

    /**
     * Listar
     */
    public function index()
    {
        /* $subtipos = SubtipoTransaccion::where(function($query){
            $query->select('tipo')
                ->from('tipos_transacciones')
                ->whereColumn('tipos_transacciones.id', 'subtipos_transacciones.tipo_transaccion_id');
        }, 'INGRESO'); */
        // Log::channel('testing')->info('Log', ['Transacciones de ingreso', (object)$transacciones]);
        /* $transacciones = DB::select(
            "select * from transacciones_bodega tb where subtipo_id in(
                select id from subtipos_transacciones st where tipo_transaccion_id in(
                    select id from tipos_transacciones tt where tipo='INGRESO'
                )
            )"
        ); */
        $results = [];
        $transacciones = TransaccionBodega::all();
        foreach ($transacciones as $transaccion) {
            if ($transaccion->subtipo->tipoTransaccion->tipo === 'INGRESO') {
                array_push($results, $transaccion);
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

        if ($transaccion->solicitante->id === auth()->user()->empleado->id) {
            try{
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
            }catch(Exception $e){
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
