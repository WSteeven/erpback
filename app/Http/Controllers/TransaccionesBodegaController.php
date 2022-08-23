<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransaccionBodegaRequest;
use App\Http\Resources\TransaccionBodegaResource;
use App\Models\TransaccionesBodega;
use Illuminate\Http\Request;

class TransaccionesBodegaController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:puede.ver.transaccion')->only('index', 'show');
        $this->middleware('can:puede.crear.transaccion')->only('store');
        $this->middleware('can:puede.editar.transaccion')->only('update');
        $this->middleware('can:puede.autorizar.transaccion')->only('autorizar');
    }

    public function index()
    {
        $transacciones = TransaccionBodegaResource::collection(TransaccionesBodega::all());
        return response()->json(['modelo' => $transacciones]);
    }


    public function store(TransaccionBodegaRequest $request)
    {
        $transaccion = TransaccionesBodega::create($request->validated());

        return response()->json(['mensaje' => 'La transacción ha sido creada con éxito', 'modelo' => $transaccion]);
    }


    public function show(TransaccionesBodega $transaccion)
    {
        return response()->json(['modelo' => new TransaccionBodegaResource($transaccion)]);
    }


    public function update(TransaccionBodegaRequest $request, TransaccionesBodega  $transaccion)
    {
        $transaccion->update($request->validated());

        return response()->json(['mensaje' => 'La transacción ha sido actualizada con éxito', 'modelo' => new TransaccionBodegaResource($transaccion)]);
    }


    public function destroy(TransaccionesBodega $transaccion)
    {
        $transaccion->delete();

        return response()->json(['mensaje' => 'La transacción ha sido eliminada con éxito', 'modelo' => $transaccion]);
    }
}
