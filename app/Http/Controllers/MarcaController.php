<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function index()
    {
        return response()->json(['modelo' => Marca::all()]);
    }


    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|unique:marcas']);
        $marca = Marca::create($request->all());

        return response()->json(['mensaje' => 'La marca ha sido creada con exito', 'modelo' => $marca]);
    }


    public function show(Marca $marca)
    {
        return response()->json(['modelo' => $marca]);
    }


    public function update(Request $request, Marca  $marca)
    {
        $request->validate(['nombre' => 'required|unique:marcas']);
        $marca->update($request->all());

        return response()->json(['mensaje' => 'La marca ha sido actualizada con exito', 'modelo' => $marca]);
    }


    public function destroy(Marca $marca)
    {
        $marca->delete();

        return response()->json(['mensaje' => 'La marca ha sido eliminada con exito', 'modelo' => $marca]);
    }
}
