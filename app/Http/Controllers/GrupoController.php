<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    public function index()
    {
        return response()->json(['modelo' => Grupo::all()]);
    }


    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|unique:grupos', 'creador_id'=>'required|exists:users,id']);
        $grupo = Grupo::create($request->all());

        return response()->json(['mensaje' => 'El grupo ha sido creado con éxito', 'modelo' => $grupo]);
    }


    public function show(Grupo $grupo)
    {
        return response()->json(['modelo' => $grupo]);
    }


    public function update(Request $request, Grupo  $grupo)
    {
        $request->validate(['nombre' => 'required|unique:grupos', 'creador_id'=>'required|exists:users,id']);
        $grupo->update($request->all());

        return response()->json(['mensaje' => 'El grupo ha sido actualizado con éxito', 'modelo' => $grupo]);
    }


    public function destroy(Grupo $grupo)
    {
        $grupo->delete();

        return response()->json(['mensaje' => 'El grupo ha sido eliminado con éxito', 'modelo' => $grupo]);
    }
}
