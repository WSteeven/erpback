<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequest;
use App\Http\Resources\ClienteResource;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function index()
    {
        return response()->json(['modelo' => ClienteResource::collection(Cliente::all())]);
    }
    /* private function reglas(){
        return [
            'empresa_id' => 'exists:empresas,id|required',//|unique:clientes,empresa_id',
            'parroquia_id' => 'required|exists:parroquias,id',//|unique:clientes,parroquia_id',
            'requiere_bodega' => 'boolean',
            'estado'=>'boolean'
        ];
        /* if(in_array($this->method_exists('PUT'))){
            $cliente = $this->route()->parameter('cliente');

            $rules['empresa_id']=['required',Rule::unique('clientes')->ignore($cliente)];
        }
        return $rules;
    } */

    public function store(ClienteRequest $request)
    {
        $cliente = Cliente::create($request->validated());

        return response()->json(['mensaje' => 'El cliente ha sido creado con éxito', 'modelo' => new ClienteResource($cliente)]);
    }


    public function show(Cliente $cliente)
    {
        return response()->json(['modelo' => new ClienteResource($cliente)]);
    }


    public function update(ClienteRequest $request, Cliente  $cliente)
    {
        $cliente->update($request->validated());

        return response()->json(['mensaje' => 'El cliente ha sido actualizado con éxito', 'modelo' => new ClienteResource($cliente)]);
    }


    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return response()->json(['mensaje' => 'El cliente ha sido eliminado con éxito', 'modelo' => new ClienteResource($cliente)]);
    }
}
