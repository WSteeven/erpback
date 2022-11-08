<?php

namespace App\Http\Controllers;

use App\Models\DetalleProductoTransaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        // Log::channel('testing')->info('Log', ['id', $request['transaccion_id']]);
        if($request['transaccion_id']){
            $results = DetalleProductoTransaccion::where('transaccion_id', $request['transaccion_id'])->get();
        }else{
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DetalleProductoTransaccion  $detalleProductoTransaccion
     * @return \Illuminate\Http\Response
     */
    public function show(DetalleProductoTransaccion $detalleProductoTransaccion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DetalleProductoTransaccion  $detalleProductoTransaccion
     * @return \Illuminate\Http\Response
     */
    public function edit(DetalleProductoTransaccion $detalleProductoTransaccion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DetalleProductoTransaccion  $detalleProductoTransaccion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetalleProductoTransaccion $detalleProductoTransaccion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DetalleProductoTransaccion  $detalleProductoTransaccion
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetalleProductoTransaccion $detalleProductoTransaccion)
    {
        //
    }
}
