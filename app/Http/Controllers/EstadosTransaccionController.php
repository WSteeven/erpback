<?php

namespace App\Http\Controllers;

use App\Models\EstadosTransaccion;
use Illuminate\Http\Request;

class EstadosTransaccionController extends Controller
{
    public function index()
    {
        return response()->json(['modelo' => EstadosTransaccion::all()]);
    }


    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|unique:estados_transacciones_bodega']);
        $estado = EstadosTransaccion::create($request->all());

        return response()->json(['mensaje' => 'El estado ha sido creado con éxito', 'modelo' => $estado]);
    }


    public function show(EstadosTransaccion $estado)
    {
        return response()->json(['modelo' => $estado]);
    }


    public function update(Request $request, EstadosTransaccion  $estado)
    {
        $request->validate(['nombre' => 'required|unique:estados_transacciones_bodega']);
        $estado->update($request->all());

        return response()->json(['mensaje' => 'El estado ha sido actualizado con éxito', 'modelo' => $estado]);
    }


    public function destroy(EstadosTransaccion $estado)
    {
        $estado->delete();

        return response()->json(['mensaje' => 'El estado ha sido eliminado con éxito', 'modelo' => $estado]);
    }
}
