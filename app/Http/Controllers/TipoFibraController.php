<?php

namespace App\Http\Controllers;

use App\Models\TipoFibra;
use Illuminate\Http\Request;

class TipoFibraController extends Controller
{
    public function index()
    {
        return response()->json(['modelo' => TipoFibra::all()]);
    }


    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|unique:tipo_fibras']);
        $tipoFibra = TipoFibra::create($request->all());

        return response()->json(['mensaje' => 'El tipo de fibra ha sido creado con exito', 'modelo' => $tipoFibra]);
    }


    public function show(TipoFibra $tipoFibra)
    {
        return response()->json(['modelo' => $tipoFibra]);
    }


    public function update(Request $request, TipoFibra  $tipoFibra)
    {
        $request->validate(['nombre' => 'required|unique:tipo_fibras']);
        $tipoFibra->update($request->all());

        return response()->json(['mensaje' => 'El tipo de fibra ha sido actualizado con exito', 'modelo' => $tipoFibra]);
    }


    public function destroy(TipoFibra $tipoFibra)
    {
        $tipoFibra->delete();

        return response()->json(['mensaje' => 'El tipo de fibra ha sido eliminado con exito', 'modelo' => $tipoFibra]);
    }
}
