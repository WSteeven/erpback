<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransaccionBodegaRequest;
use App\Http\Resources\TransaccionBodegaResource;
use App\Models\TransaccionesBodega;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class TransaccionesBodegaController extends Controller
{
    private $entidad = 'Transaccion';
    public function __construct()
    {
        $this->middleware('can:puede.ver.transaccion')->only('index', 'show');
        $this->middleware('can:puede.crear.transaccion')->only('store');
        $this->middleware('can:puede.editar.transaccion')->only('update');
        $this->middleware('can:puede.autorizar.transaccion')->only('autorizar');
    }

    /**
     * Listar
     */
    public function index()
    {
        $results = TransaccionBodegaResource::collection(TransaccionesBodega::all());
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(TransaccionBodegaRequest $request)
    {
        //Respuesta
        $transaccion = TransaccionesBodega::create($request->validated());
        $modelo = new TransaccionBodegaResource($transaccion);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        //Guardar la autorizacion con su observacion 
        if($request->observacion_aut){
            $transaccion->autorizaciones()->attach($request->autorizacion_id, ['observacion'=>$request->observacion_aut]);
        }else{
            $transaccion->autorizaciones()->attach($request->autorizacion_id);
        }

        //Guardar el estado con su observacion
        if($request->observacion_est){
            //Log::channel('testing')->info('Log', ['Segundo IF:', $transaccion->observacion_est]);
            $transaccion->estados()->attach($request->estado_id, ['observacion'=>$request->observacion_est]);
        }else{
            $transaccion->estados()->attach($request->estado_id);
        }

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Consultar
     */
    public function show(TransaccionesBodega $transaccion)
    {
        $modelo = new TransaccionBodegaResource($transaccion);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(TransaccionBodegaRequest $request, TransaccionesBodega  $transaccion)
    {
        $transaccion->update($request->validated());
        $modelo = new TransaccionBodegaResource($transaccion->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(TransaccionesBodega $transaccion)
    {
        $transaccion->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
