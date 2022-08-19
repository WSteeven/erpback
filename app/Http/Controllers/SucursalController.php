<?php

namespace App\Http\Controllers;

use App\Http\Requests\SucursalRequest;
use App\Http\Resources\SucursalResource;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Psy\Sudo;

class SucursalController extends Controller
{
    public function index()
    {
        $sucursal = SucursalResource::collection(Sucursal::all());
        return response()->json(['modelo' => $sucursal]);
    }


    public function store(SucursalRequest $request)
    {
        $sucursal = Sucursal::create($request->validated());

        return response()->json(['mensaje' => 'La sucursal ha sido creada con éxito', 'modelo' => $sucursal]);
    }


    public function show(Sucursal $sucursal)
    {
        return response()->json(['modelo' => new SucursalResource($sucursal)]);
    }


    public function update(SucursalRequest $request, Sucursal  $sucursal)
    {
        $sucursal->update($request->validated());

        return response()->json(['mensaje' => 'La sucursal ha sido actualizada con éxito', 'modelo' => $sucursal]);
    }


    public function destroy(Sucursal $sucursal)
    {
        $sucursal->delete();

        return response()->json(['mensaje' => 'La sucursal ha sido eliminada con éxito', 'modelo' => $sucursal]);
    }
}
