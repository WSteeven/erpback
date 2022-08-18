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
        $sucursal = Sucursal::create($request->all());

        return response()->json(['mensaje' => 'La sucursal ha sido creada con exito', 'modelo' => $sucursal]);
    }


    public function show(Sucursal $sucursal)
    {
        return response()->json(['modelo' => new SucursalResource($sucursal)]);
    }


    public function update(SucursalRequest $request, Sucursal  $sucursal)
    {
        $sucursal->update($request->all());

        return response()->json(['mensaje' => 'La sucursal ha sido actualizada con exito', 'modelo' => $sucursal]);
    }


    public function destroy(Sucursal $sucursal)
    {
        $sucursal->delete();

        return response()->json(['mensaje' => 'La sucursal ha sido eliminada con exito', 'modelo' => $sucursal]);
    }
}
