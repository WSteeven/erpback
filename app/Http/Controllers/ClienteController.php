<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequest;
use App\Http\Resources\ClienteResource;
use App\Models\Cliente;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Src\Shared\Utils;

class ClienteController extends Controller
{
    private $entidad = 'Cliente';
    /**
     * Listar 
     */
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
    /**
     * Guardar
     */
    public function store(ClienteRequest $request)
    {
        //Respuesta
        $modelo = Cliente::create($request->validated());
        $modelo = new ClienteResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Consultar
     */
    public function show(Cliente $cliente)
    {
        $modelo = new ClienteResource($cliente);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(ClienteRequest $request, Cliente  $cliente)
    {
        // Respuesta
        $cliente->update($request->validated());
        $modelo = new ClienteResource($cliente->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
