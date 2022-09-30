<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransaccionBodegaRequest;
use App\Http\Resources\TransaccionBodegaResource;
use App\Models\SubtipoTransaccion;
use App\Models\TransaccionBodega;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class TransaccionBodegaController extends Controller
{
    private $entidad = 'Transaccion';
    public function __construct()
    {
        $this->middleware('can:puede.ver.transaccion')->only('index', 'show');
        //$this->middleware('can:puede.crear.transaccion')->only('store');
        $this->middleware('can:puede.crear.transacciones')->only('store');
        $this->middleware('can:puede.editar.transaccion')->only('update');
        $this->middleware('can:puede.autorizar.transaccion')->only('autorizar');
    }

    /**
     * Listar
     */
    public function index()
    {
        $results = TransaccionBodegaResource::collection(TransaccionBodega::all());
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(TransaccionBodegaRequest $request)
    {
        Log::channel('testing')->info('Log', ['Request recibida:', $request->all()]);
        
        $subtipo = SubtipoTransaccion::find($request->subtipo);
        $tipo_contable = $subtipo->tipoTransaccion->tipo;

        if(!(auth()->user()->hasRole([User::ROL_COORDINADOR, User::ROL_BODEGA])) && $tipo_contable==='INGRESO'){
            Log::channel('testing')->info('Log', ['No pasó la validacion, solo los coordinadores y bodegueros pueden hacer ingresos']);
            return response()->json(compact('Este usuario no puede realizar ingreso de materiales', 'modelo'));
        }else{
            Log::channel('testing')->info('Log', ['Pasó la validacion, solo los coordinadores y bodegueros pueden hacer ingresos']);
            //Adaptacion de foreign keys
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
            
            Log::channel('testing')->info('Log', ['Datos modificados:', $datos]);
    
            //Respuesta
            $transaccion = TransaccionBodega::create($datos);
            $modelo = new TransaccionBodegaResource($transaccion);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
    
            //Guardar la autorizacion con su observacion 
            if ($request->observacion_aut) {
                $transaccion->autorizaciones()->attach($datos['autorizacion'], ['observacion' => $datos['observacion_aut']]);
            } else {
                $transaccion->autorizaciones()->attach($datos['autorizacion_id']);
            }
    
            //Guardar el estado con su observacion
            if ($request->observacion_est) {
                //Log::channel('testing')->info('Log', ['Segundo IF:', $transaccion->observacion_est]);
                $transaccion->estados()->attach($datos['estado_id'], ['observacion' => $datos['observacion_est']]);
            } else {
                $transaccion->estados()->attach($datos['estado_id']);
            }

            foreach($request->listadoProductosSeleccionados as $listado){
                Log::channel('testing')->info('Log', ['Listado recibido en el foreach:', $listado]);
                $transaccion->productos()->attach($listado['id'], ['cantidad_inicial'=>$listado['cantidades']]);
            }
            Log::channel('testing')->info('Log', ['Los productos se guardaron en el detalle']);
            return response()->json(compact('mensaje', 'modelo'));
        }
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
    public function update(TransaccionBodegaRequest $request, TransaccionBodega  $transaccion)
    {
        $transaccion->update($request->validated());
        $modelo = new TransaccionBodegaResource($transaccion->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
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
