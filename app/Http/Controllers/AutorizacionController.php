<?php

namespace App\Http\Controllers;

use App\Models\Autorizacion;
use Illuminate\Http\Request;

class AutorizacionController extends Controller
{
    public function index()
    {
        return response()->json(['modelo' => Autorizacion::all()]);
    }


    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|unique:autorizaciones']);
        $autorizacion = Autorizacion::create($request->all());

        return response()->json(['mensaje' => 'La autorización ha sido creada con éxito', 'modelo' => $autorizacion]);
    }


    public function show(Autorizacion $autorizacion)
    {
        return response()->json(['modelo' => $autorizacion]);
    }


    public function update(Request $request, Autorizacion  $autorizacion)
    {
        $request->validate(['nombre' => 'required|unique:autorizacions']);
        $autorizacion->update($request->all());

        return response()->json(['mensaje' => 'La autorización ha sido actualizada con éxito', 'modelo' => $autorizacion]);
    }


    public function destroy(Autorizacion $autorizacion)
    {
        $autorizacion->delete();

        return response()->json(['mensaje' => 'La autorización ha sido eliminada con éxito', 'modelo' => $autorizacion]);
    }
}
