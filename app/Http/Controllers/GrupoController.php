<?php

namespace App\Http\Controllers;

use App\Http\Requests\GrupoRequest;
use App\Http\Resources\GrupoResource;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class GrupoController extends Controller
{
    private $entidad = 'Grupo';

    /**
     * Listar
     */
    public function index()
    {
        $results = GrupoResource::collection(Grupo::all());
        return response()->json(compact('results'));
    }


    /**
     * Guardar
     */
    public function store(GrupoRequest $request)
    {
        //Respuesta
        $modelo = Grupo::create($request->validated());
        $modelo = new GrupoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Consultar
     */
    public function show(Grupo $grupo)
    {
        $modelo = new GrupoResource($grupo);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(GrupoRequest $request, Grupo  $grupo)
    {
        // Respuesta
        $grupo->update($request->validated());
        $modelo = new GrupoResource($grupo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Eliminar
     */
    public function destroy(Grupo $grupo)
    {
        $grupo->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
