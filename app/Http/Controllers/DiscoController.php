<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiscoRequest;
use App\Http\Resources\DiscoResource;
use App\Models\Disco;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class DiscoController extends Controller
{
    private $entidad = 'Disco';

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $results = Disco::all();
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(DiscoRequest $request)
    {
        //Respuesta 
        $modelo = Disco::create($request->validated());
        $modelo = new DiscoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Disco $disco)
    {
        $modelo = new DiscoResource($disco);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */

    public function update(DiscoRequest $request, Disco $disco)
    {
        //Respuesta
        $disco->update($request->validated());
        $modelo = new DiscoResource($disco->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(Disco $disco)
    {
        $disco->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');

        return response()->json(compact('mensaje'));
    }
}
