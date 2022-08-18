<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;

class ModeloController extends Controller
{
    public function index()
    {
        return response()->json(['modelo' => Modelo::all()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'marca_id' =>'required|exists:marcas,id']);
        $modelo = Modelo::create($request->all());

        return response()->json(['mensaje' => 'El modelo ha sido creada con exito', 'modelo' => $modelo]);
    }


    public function show(Modelo $modelo)
    {
        return response()->json(['modelo' => $modelo]);
    }


    public function update(Request $request, Modelo  $modelo)
    {
        $request->validate([
            'nombre' => 'required|string',
            'marca_id' =>'required|exists:marcas,id']);
        $modelo->update($request->all());

        return response()->json(['mensaje' => 'El modelo ha sido actualizada con exito', 'modelo' => $modelo]);
    }


    public function destroy(Modelo $modelo)
    {
        $modelo->delete();

        return response()->json(['mensaje' => 'El modelo ha sido eliminada con exito', 'modelo' => $modelo]);
    }
}
