<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComprobanteRequest;
use App\Http\Resources\ComprobanteResource;
use App\Models\Comprobante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ComprobanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = Comprobante::all();
        $results = ComprobanteResource::collection($results);
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
     * @param  \App\Models\Comprobante  $comprobante
     * @return \Illuminate\Http\Response
     */
    public function show(Comprobante $comprobante)
    {
        Log::channel('testing')->info('Log', ['Show de comprobante:', $comprobante ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comprobante  $comprobante
     * @return \Illuminate\Http\Response
     */
    public function update(ComprobanteRequest $request, int $comprobante)
    {
        // Log::channel('testing')->info('Log', ['[Update] El comprobante a modificar es:', $request->all(), $comprobante]);
        $comprobante = Comprobante::where('transaccion_id', $comprobante)->first();
        // Log::channel('testing')->info('Log', ['[Despues de update] El comprobante a modificar es:', $comprobante]);
        $datos = $request->validated();
        $comprobante->update($datos);

        $modelo = new ComprobanteResource($comprobante);
        $mensaje = 'Comprobante actualizado correctamente';
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comprobante  $comprobante
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comprobante $comprobante)
    {
        //
    }
}
