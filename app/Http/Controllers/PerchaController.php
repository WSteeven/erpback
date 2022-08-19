<?php

namespace App\Http\Controllers;

use App\Models\Percha;
use Illuminate\Http\Request;

class PerchaController extends Controller
{
    public function index()
    {
        return response()->json(['modelo' => Percha::all()]);
    }


    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string', 'sucursal_id'=>'required|exists:sucursales,id']);
        $percha = Percha::create($request->all());

        return response()->json(['mensaje' => 'La percha ha sido creada con éxito', 'modelo' => $percha]);
    }


    public function show(Percha $percha)
    {
        return response()->json(['modelo' => $percha]);
    }


    public function update(Request $request, Percha  $percha)
    {
        $request->validate(['nombre' => 'required|string', 'sucursal_id'=>'required|exists:sucursales,id']);
        $percha->update($request->all());

        return response()->json(['mensaje' => 'La percha ha sido actualizada con éxito', 'modelo' => $percha]);
    }


    public function destroy(Percha $percha)
    {
        $percha->delete();

        return response()->json(['mensaje' => 'La percha ha sido eliminada con éxito', 'modelo' => $percha]);
    }
}
