<?php

namespace App\Http\Controllers;

use App\Models\Hilo;
use Illuminate\Http\Request;

class HiloController extends Controller
{
    public function index()
    {
        return response()->json(['modelo' => Hilo::all()]);
    }


    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|unique:hilos']);
        $hilo = Hilo::create($request->all());

        return response()->json(['mensaje' => 'El hilo ha sido creado con exito', 'modelo' => $hilo]);
    }


    public function show(Hilo $hilo)
    {
        return response()->json(['modelo' => $hilo]);
    }


    public function update(Request $request, Hilo  $hilo)
    {
        $request->validate(['nombre' => 'required|unique:hilos']);
        $hilo->update($request->all());

        return response()->json(['mensaje' => 'El hilo ha sido actualizado con exito', 'modelo' => $hilo]);
    }


    public function destroy(Hilo $hilo)
    {
        $hilo->delete();

        return response()->json(['mensaje' => 'El hilo ha sido eliminado con exito', 'modelo' => $hilo]);
    }
}
