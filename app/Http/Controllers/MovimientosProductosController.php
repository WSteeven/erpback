<?php

namespace App\Http\Controllers;

use App\Models\MovimientosProductos;
use Illuminate\Http\Request;

class MovimientosProductosController extends Controller
{
    public function index()
    {
        $movimiento = MovimientosProductos::all();
        return response()->json(['modelo' => $movimiento]);
    }

    public function store(Request $request)
    {

        $movimientoCreado = MovimientosProductos::create($request->all());
        return response()->json([
            'mensaje' => 'Movimiento creado',
            'modelo' => $movimientoCreado,
        ]);
    }



    public function show(MovimientosProductos $movimiento)
    {
        return response()->json(['modelo' => $movimiento]);
    }


    public function update(Request $request, MovimientosProductos $movimiento)
    {
        $datosValidados = $request->all();

        $movimiento->update($datosValidados);
        return response()->json(['mensaje' => 'Movimiento actualizado con exito', 'modelo' => $movimiento]);
    }

    public function destroy(MovimientosProductos $movimiento)
    {
        $movimiento->delete();
        return response()->json(['mensaje' => 'Movimiento eliminado con exito']);
    }
}
