<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmpleadoRequest;
use App\Http\Resources\EmpleadoResource;
use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleado = EmpleadoResource::collection(Empleado::all());
        return response()->json(['modelo' => $empleado]);
    }


    public function store(EmpleadoRequest $request)
    {
        $empleado = Empleado::create($request->validated());

        return response()->json(['mensaje' => 'El empleado ha sido creado con éxito', 'modelo' => $empleado]);
    }


    public function show(Empleado $empleado)
    {
        return response()->json(['modelo' => new EmpleadoResource($empleado)]);
    }


    public function update(EmpleadoRequest $request, Empleado  $empleado)
    {
        $empleado->update($request->validated());

        return response()->json(['mensaje' => 'El empleado ha sido actualizado con éxito', 'modelo' => $empleado]);
    }


    public function destroy(Empleado $empleado)
    {
        $empleado->delete();

        return response()->json(['mensaje' => 'El empleado ha sido eliminado con éxito', 'modelo' => $empleado]);
    }
}
