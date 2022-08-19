<?php

namespace App\Http\Controllers;

use App\Models\Piso;
use Illuminate\Http\Request;

class PisoController extends Controller
{
    public function index()
    {
        return response()->json(['modelo' => Piso::all()]);
    }

    public function store(Request $request)
    {
        $request->validate(['piso' => 'required|string', 'columna'=>'required|string']);
        $piso = Piso::create($request->all());

        return response()->json(['mensaje' => 'El piso ha sido creado con éxito', 'modelo' => $piso]);
    }

    public function show(Piso $piso)
    {
        return response()->json(['modelo' => $piso]);
    }

    public function update(Request $request, Piso  $piso)
    {
        $request->validate(['piso' => 'required|string', 'columna'=>'required|string']);
        $piso->update($request->all());

        return response()->json(['mensaje' => 'El piso ha sido actualizado con éxito', 'modelo' => $piso]);
    }

    public function destroy(Piso $piso)
    {
        $piso->delete();
        return response()->json(['mensaje' => 'El piso ha sido eliminado con éxito', 'modelo' => $piso]);
    }
}
