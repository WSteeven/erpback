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
        $request->validate(['codigo' => 'required|string','percha_id' => 'required|exists:percha,id', 'piso_id' => 'required|exists:piso,id']);
        $ubicacion = Ubicacion::create($request->all());

        return response()->json(['mensaje' => 'La ubicación ha sido creada con éxito', 'modelo' => $ubicacion]);
    }


    public function show(Ubicacion $ubicacion)
    {
        return response()->json(['modelo' => $ubicacion]);
    }


    public function update(Request $request, Ubicacion  $ubicacion)
    {
        $request->validate(['codigo' => 'required|string','percha_id' => 'required|exists:percha,id', 'piso_id' => 'required|exists:piso,id']);
        $ubicacion->update($request->all());

        return response()->json(['mensaje' => 'La ubicación ha sido actualizada con éxito', 'modelo' => $ubicacion]);
    }


    public function destroy(Ubicacion $ubicacion)
    {
        $ubicacion->delete();

        return response()->json(['mensaje' => 'La ubicación ha sido eliminada con éxito', 'modelo' => $ubicacion]);
    }
}
