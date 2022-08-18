<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmpresaRequest;
use App\Http\Resources\EmpresaResource;
use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = EmpresaResource::collection(Empresa::all());
        return response()->json(['modelo' => $empresas]);
    }


    public function store(EmpresaRequest $request)
    {
        $empresa = Empresa::create($request->validated());

        return response()->json(['mensaje' => 'La empresa ha sido creada con exito', 'modelo' => $empresa]);
    }


    public function show(Empresa $empresa)
    {
        return response()->json(['modelo' => $empresa]);
    }


    public function update(EmpresaRequest $request, Empresa  $empresa)
    {
        $request->validate(['nombre' => 'required|unique:categorias']);
        $empresa->update($request->validated());

        return response()->json(['mensaje' => 'La empresa ha sido actualizada con exito', 'modelo' => $empresa]);
    }


    public function destroy(Empresa $empresa)
    {
        $empresa->delete();

        return response()->json(['mensaje' => 'La empresa ha sido eliminada con exito', 'modelo' => $empresa]);
    }
}
