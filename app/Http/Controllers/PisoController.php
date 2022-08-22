<?php

namespace App\Http\Controllers;

use App\Models\Piso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PisoController extends Controller
{
    public function index()
    {
        return response()->json(['modelo' => Piso::all()]);
    }

    public function store(Request $request)
    {
        $messages =[
            'piso.unique'=>'Ya existe el piso y columna que intentas ingresar'
        ];
        $rules = [
            'piso'=>'unique:pisos,piso,NULL,id,columna,'.$request->columna,
            'columna'=>'required|string'];

        $validador = Validator::make($request->all(),$rules, $messages);
        $validador->validate();
        $piso = Piso::create($validador->validated());

        return response()->json(['mensaje' => 'El piso ha sido creado con éxito', 'modelo' => $piso]);
    }

    public function show(Piso $piso)
    {
        return response()->json(['modelo' => $piso]);
    }

    public function update(Request $request, Piso  $piso)
    {
        $messages =[
            'piso.unique'=>'Ya existe el piso y columna que intentas actualizar'
        ];
        $rules = [
            'piso'=>'unique:pisos,piso,NULL,id,columna,'.$request->columna,
            'columna'=>'required|string'];

        $validador = Validator::make($request->all(),$rules, $messages);
        $validador->validate();
        $piso->update($validador->validated());

        return response()->json(['mensaje' => 'El piso ha sido actualizado con éxito', 'modelo' => $piso]);
    }

    public function destroy(Piso $piso)
    {
        $piso->delete();
        return response()->json(['mensaje' => 'El piso ha sido eliminado con éxito', 'modelo' => $piso]);
    }
}
