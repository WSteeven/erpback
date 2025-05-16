<?php

namespace App\Http\Controllers;

use App\Http\Requests\GrupoRequest;
use App\Http\Resources\GrupoResource;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class GrupoController extends Controller
{
    private string $entidad = 'Grupo';

    public function listar()
    {
        $campos = explode(',', request('campos'));

        if (request('campos')) {
            return Grupo::ignoreRequest(['campos'])->filter()->latest()->get($campos);
        } else {
            return GrupoResource::collection(Grupo::filter()->latest()->get());
        }
    }

    /**
     * Listar
     */
    public function index()
    {
        $results = $this->listar();
        return response()->json(compact('results'));
    }


    /**
     * Guardar
     */
    public function store(GrupoRequest $request)
    {
        //Respuesta
        $datos = $request->validated();
        $datos['coordinador_id'] = $datos['coordinador'];

        $modelo = Grupo::create($datos);
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
        $datos = $request->validated();
        $datos['coordinador_id'] = $datos['coordinador'];

        $grupo->update($datos);
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
