<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        return response()->json(['modelo' => Cliente::all()]);
    }
    private function reglas(){
        return [
            'empresa_id' => 'required|exists:empresas,id',
            'parroquia_id' => 'required|exists:parroquias,id',
            'requiere_bodega' => 'boolean',
            'estado'=>'boolean'
        ];
    }

    public function store(Request $request)
    {
        $request->validate($this->reglas());
        $cliente = Cliente::create($request->all());

        return response()->json(['mensaje' => 'El cliente ha sido creado con éxito', 'modelo' => $cliente]);
    }


    public function show(Cliente $cliente)
    {
        return response()->json(['modelo' => $cliente]);
    }


    public function update(Request $request, Cliente  $cliente)
    {
        $request->validate($this->reglas());
        $cliente->update($request->all());

        return response()->json(['mensaje' => 'El cliente ha sido actualizado con éxito', 'modelo' => $cliente]);
    }


    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return response()->json(['mensaje' => 'El cliente ha sido eliminado con éxito', 'modelo' => $cliente]);
    }
}
