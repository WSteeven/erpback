<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {

        return response()->json(['modelo' => Categoria::all()]);
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|unique:categorias']);
        $categoria = Categoria::create($request->all());

        return response()->json(['mensaje' => 'La categoría ha sido creada con exito', 'modelo' => $categoria]);
    }

    public function show(Categoria $categoria)
    {
        return response()->json(['modelo' => $categoria]);
    }

    public function update(Request $request, Categoria  $categoria)
    {
        $request->validate(['nombre' => 'required|unique:categorias']);
        $categoria->update($request->all());

        return response()->json(['mensaje' => 'La categoría ha sido actualizada con exito', 'modelo' => $categoria]);
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();

        return response()->json(['mensaje' => 'La categoría ha sido eliminada con exito', 'modelo' => $categoria]);
    }
}
