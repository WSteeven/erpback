<?php

namespace App\Http\Controllers;

use App\Http\Requests\DetalleProductoTransaccionRequest;
use App\Http\Resources\DetalleProductoTransaccionResource;
use App\Models\DetalleProductoTransaccion;
use App\Models\DevolucionTransaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class DetalleProductoTransaccionController extends Controller
{

    private $entidad = 'Detalle de producto en Transacción';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Log::channel('testing')->info('Log', ['id', $request->all()]);
        Log::channel('testing')->info('Log', ['transaccion_id', $request['transaccion_id']]);
        Log::channel('testing')->info('Log', ['inventario_id', $request['inventario_id']]);
        if ($request['transaccion_id']) {
            $results = DetalleProductoTransaccion::where('transaccion_id', $request['transaccion_id'])->get();
            if ($request['inventario_id']) {
                $results = DetalleProductoTransaccion::where('transaccion_id', $request['transaccion_id'])->where('inventario_id', $request['inventario_id'])->get();
            }
        } else {
            $results = DetalleProductoTransaccion::all();
        }
        // Log::channel('testing')->info('Log', ['Datos obtenidos:', $results, 'transaccion_id', $request['transaccion_id']]);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DetalleProductoTransaccionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DetalleProductoTransaccion  $detalleProductoTransaccion
     * @return \Illuminate\Http\Response
     */
    public function show(DetalleProductoTransaccion $detalle)
    {
        $modelo = new DetalleProductoTransaccionResource($detalle);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DetalleProductoTransaccion  $detalleProductoTransaccion
     * @return \Illuminate\Http\Response
     */
    public function update(DetalleProductoTransaccionRequest $request, DetalleProductoTransaccion $detalle)
    {
        Log::channel('testing')->info('Log', ['metodo update del controlador DetalleProductoTransaccionController', $request->all()]);
        // $datos = $request->validated();
        // Log::channel('testing')->info('Log', ['DetalleProductoTransaccion', $detalle]);
        // Log::channel('testing')->info('Log', ['DATOS DetalleProductoTransaccion', $datos]);
        // //Se crea la devolucion
        // $devolucion = new DevolucionTransaccion(['cantidad'=>$request->cantidad_final]);
        // $detalle->devoluciones()->save($devolucion);
        // $detalle->cantidad_final += $datos['cantidad_final'];
        // $detalle->save();
        // $modelo = new DetalleProductoTransaccionResource($detalle->refresh());
        // $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DetalleProductoTransaccion  $detalleProductoTransaccion
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetalleProductoTransaccion $detalle)
    {
        $detalle->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
