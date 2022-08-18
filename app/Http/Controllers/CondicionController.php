<?php

namespace App\Http\Controllers;

use App\Models\Condicion;
use Illuminate\Http\Request;

class CondicionController extends Controller
{
    public function index()
    {
        return response()->json(['modelo' => Condicion::all()]);
    }


    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|unique:condiciones_de_productos']);
        $condicion = Condicion::create($request->all());

        return response()->json(['mensaje' => 'La condición ha sido creada con éxito', 'modelo' => $condicion]);
    }


    public function show(Condicion $condicion)
    {
        return response()->json(['modelo' => $condicion]);
    }


    public function update(Request $request, Condicion  $condicion)
    {
        $request->validate(['nombre' => 'required|unique:condiciones_de_productos']);
        $condicion->update($request->all());

        return response()->json(['mensaje' => 'La condición ha sido actualizada con éxito', 'modelo' => $condicion]);
    }


    public function destroy(Condicion $condicion)
    {
        $condicion->delete();

        return response()->json(['mensaje' => 'La condición ha sido eliminada con éxito', 'modelo' => $condicion]);
    }
}
