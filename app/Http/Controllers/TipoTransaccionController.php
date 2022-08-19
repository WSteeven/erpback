<?php

namespace App\Http\Controllers;

use App\Models\TipoTransaccion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TipoTransaccionController extends Controller
{
    public function index()
    {
        return response()->json(['modelo' => TipoTransaccion::all()]);
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|unique:tipo_de_transacciones,nombre', 'tipo'=>Rule::in(TipoTransaccion::INGRESO, TipoTransaccion::EGRESO)]);
        $tipo = TipoTransaccion::create($request->all());

        return response()->json(['mensaje' => 'El tipo ha sido creado con éxito', 'modelo' => $tipo]);
    }

    public function show(TipoTransaccion $tipo)
    {
        return response()->json(['modelo' => $tipo]);
    }

    public function update(Request $request, TipoTransaccion  $tipo)
    {
        $request->validate(['nombre' => 'required|string|unique:tipo_de_transacciones,nombre', 'tipo'=>Rule::in(TipoTransaccion::INGRESO, TipoTransaccion::EGRESO)]);
        $tipo->update($request->all());

        return response()->json(['mensaje' => 'El tipo ha sido actualizado con éxito', 'modelo' => $tipo]);
    }

    public function destroy(TipoTransaccion $tipo)
    {
        $tipo->delete();
        return response()->json(['mensaje' => 'El tipo ha sido eliminado con éxito', 'modelo' => $tipo]);
    }
}
