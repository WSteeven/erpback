<?php

namespace App\Http\Controllers;

use App\Models\Ubicacion;
use Illuminate\Http\Request;

class UbicacionController extends Controller
{
    public function index()
    {
        return response()->json(['modelo' => Ubicacion::all()]);
    }


    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|unique:ubicaciones']);
        $Ubicacion = Ubicacion::create($request->all());

        return response()->json(['mensaje' => 'La categoría ha sido creada con exito', 'modelo' => $Ubicacion]);
    }


    public function show(Ubicacion $Ubicacion)
    {
        return response()->json(['modelo' => $Ubicacion]);
    }


    public function update(Request $request, Ubicacion  $Ubicacion)
    {
        $request->validate(['nombre' => 'required|unique:Ubicacions']);
        $Ubicacion->update($request->all());

        return response()->json(['mensaje' => 'La categoría ha sido actualizada con exito', 'modelo' => $Ubicacion]);
    }


    public function destroy(Ubicacion $Ubicacion)
    {
        $Ubicacion->delete();

        return response()->json(['mensaje' => 'La categoría ha sido eliminada con exito', 'modelo' => $Ubicacion]);
    }
}
