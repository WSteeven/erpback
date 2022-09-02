<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequest;
use App\Http\Resources\ClienteResource;
use App\Models\Cliente;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $search = $request['search'];
        $results = [];

        if ($search) {
            $empresa = Empresa::select('id')->where('razon_social', 'LIKE', '%' . $search . '%')->first();
            // Log::channel('testing')->info('Log', ['empresa', $empresa->id]);

            if ($empresa) $results = ClienteResource::collection(Cliente::where('empresa_id', $empresa->id)->get());
        } else {
            $results = ClienteResource::collection(Cliente::all());
        }

        return response()->json(compact('results'));
    }

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
