<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClienteFinalResource;
use Illuminate\Http\Request;
use App\Models\ClienteFinal;

class ClienteFinalController extends Controller
{
    private $entidad = 'Cliente final';

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $results = ClienteFinalResource::collection(ClienteFinal::all());
        return response()->json(compact('results'));
    }


    public function show(ClienteFinal $cliente_final)
    {
        $modelo = new ClienteFinalResource($cliente_final);
        return response()->json(compact('modelo'));
    }
}
