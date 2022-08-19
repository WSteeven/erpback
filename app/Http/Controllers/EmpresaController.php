<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmpresaRequest;
use App\Http\Resources\EmpresaResource;
use App\Models\Empresa;

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

        return response()->json(['mensaje' => 'La empresa ha sido creada con éxito', 'modelo' => new EmpresaResource($empresa)]);
    }


    public function show(Empresa $empresa)
    {
        return response()->json(['modelo' => new EmpresaResource($empresa)]);
    }


    public function update(EmpresaRequest $request, Empresa  $empresa)
    {
        $empresa->update($request->validated());

        return response()->json(['mensaje' => 'La empresa ha sido actualizada con éxito', 'modelo' => new EmpresaResource($empresa)]);
    }


    public function destroy(Empresa $empresa)
    {
        $empresa->delete();

        return response()->json(['mensaje' => 'La empresa ha sido eliminada con éxito', 'modelo' => new EmpresaResource($empresa)]);
    }
}
