<?php

namespace App\Http\Controllers;

use App\Models\Percha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PerchaController extends Controller
{
    public function index()
    {
        return response()->json(['modelo' => Percha::all()]);
    }


    public function store(Request $request)
    {
        $messages = ['nombre.unique'=>'La percha ya existe en esta sucursal'];
        $rules = [
            'nombre' => 'unique:perchas,nombre,NULL,id,sucursal_id,'.$request->sucursal_id,
            'sucursal_id' => 'required|exists:sucursales,id|unique:perchas,nombre'
        ];
        $validador = Validator::make($request->all(), $rules, $messages);

        $percha = Percha::create($validador->validated());

        return response()->json(['mensaje' => 'La percha ha sido creada con éxito', 'modelo' => $percha]);
    }


    public function show(Percha $percha)
    {
        return response()->json(['modelo' => $percha]);
    }


    public function update(Request $request, Percha  $percha)
    {
        $messages = ['nombre.unique'=>'La percha ya existe en esta sucursal'];
        $rules = [
            'nombre' => 'unique:perchas,nombre,NULL,id,sucursal_id,'.$request->sucursal_id,
            'sucursal_id' => 'required|exists:sucursales,id|unique:perchas,nombre'
        ];
        $validador = Validator::make($request->all(), $rules, $messages);

        $percha->update($validador->validated());

        return response()->json(['mensaje' => 'La percha ha sido actualizada con éxito', 'modelo' => $percha]);
    }


    public function destroy(Percha $percha)
    {
        $percha->delete();

        return response()->json(['mensaje' => 'La percha ha sido eliminada con éxito', 'modelo' => $percha]);
    }
}
