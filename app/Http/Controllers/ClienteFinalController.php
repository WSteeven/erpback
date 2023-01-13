<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteFinalRequest;
use App\Http\Resources\ClienteFinalResource;
use Illuminate\Http\Request;
use App\Models\ClienteFinal;
use Src\Shared\Utils;

class ClienteFinalController extends Controller
{
    private $entidad = 'Cliente final';

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $cliente = $request['cliente'];

        if ($cliente) $results = ClienteFinalResource::collection(ClienteFinal::where('cliente_id', $cliente)->get());
        else $results = ClienteFinalResource::collection(ClienteFinal::all());
        
        return response()->json(compact('results'));
    }


    public function store(ClienteFinalRequest $request)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['provincia_id'] = $request->safe()->only(['provincia'])['provincia'];
        $datos['canton_id'] = $request->safe()->only(['canton'])['canton'];
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];

        $modelo = ClienteFinal::create($datos);

        $modelo = new ClientefinalResource($modelo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }


    public function update(ClienteFinalRequest $request, ClienteFinal $clienteFinal)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['provincia_id'] = $request->safe()->only(['provincia'])['provincia'];
        $datos['canton_id'] = $request->safe()->only(['canton'])['canton'];
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];

        $clienteFinal->update($datos);

        $modelo = new ClientefinalResource($clienteFinal->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }


    public function show(ClienteFinal $cliente_final)
    {
        $modelo = new ClienteFinalResource($cliente_final);
        return response()->json(compact('modelo'));
    }
}
