<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        return response()->json(['modelo' => Proveedor::all()]);
    }


    public function store(Request $request)
    {
        $request->validate(['empresa_id' => 'required|exists:empresas,id', 'estado'=>'boolean']);
        $proveedor = Proveedor::create($request->all());

        return response()->json(['mensaje' => 'El proveedor ha sido creado con éxito', 'modelo' => $proveedor]);
    }


    public function show(Proveedor $proveedor)
    {
        return response()->json(['modelo' => $proveedor]);
    }


    public function update(Request $request, Proveedor  $proveedor)
    {
        $request->validate(['empresa_id' => 'required|exists:empresas,id', 'estado'=>'boolean']);
        $proveedor->update($request->all());

        return response()->json(['mensaje' => 'El proveedor ha sido actualizado con éxito', 'modelo' => $proveedor]);
    }


    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();

        return response()->json(['mensaje' => 'El proveedor ha sido eliminado con éxito', 'modelo' => $proveedor]);
    }
}
