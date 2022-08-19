<?php

namespace App\Http\Controllers;

use App\Models\NombreProducto;
use Illuminate\Http\Request;

class NombresProductosController extends Controller
{
    public function index()
    {
        return response()->json(['modelo' => NombreProducto::all()]);
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|unique:categorias']);
        $nombreProducto = NombreProducto::create($request->all());

        return response()->json(['mensaje' => 'El nombre de producto ha sido creado con éxito', 'modelo' => $nombreProducto]);
    }

    public function show(NombreProducto $nombreProducto)
    {
        return response()->json(['modelo' => $nombreProducto]);
    }

    public function update(Request $request, NombreProducto  $nombreProducto)
    {
        $request->validate(['nombre' => 'required|string']);
        $nombreProducto->update($request->all());

        return response()->json(['mensaje' => 'El nombre de producto ha sido actualizado con éxito', 'modelo' => $nombreProducto]);
    }

    public function destroy(NombreProducto $nombreProducto)
    {
        $nombreProducto->delete();
        return response()->json(['mensaje' => 'El nombre de producto ha sido eliminado con éxito', 'modelo' => $nombreProducto]);
    }
}
