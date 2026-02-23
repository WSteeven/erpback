<?php

namespace App\Http\Controllers\Tareas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tareas\CoordenadasRequest;
use App\Http\Resources\Tareas\CoordenadasResource;
use App\Models\Tareas\Coordenadas;
use Src\Shared\Utils;

class CoordenadasController extends Controller
{
    private string $entidad = 'Coordenadas';

    /**
     * Listar
     */
    public function index()
    {
        $results = Coordenadas::filter()->orderBy('id', 'desc')->get();
        $results = CoordenadasResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(CoordenadasRequest $request)
    {
        $datos = $request->validated();

        $modelo = Coordenadas::create($datos);
        $modelo = new CoordenadasResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Coordenadas $coordenada)
    {
        $modelo = new CoordenadasResource($coordenada);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(CoordenadasRequest $request, Coordenadas $coordenada)
    {
        $datos = $request->validated();

        $coordenada->update($datos);
        $modelo = new CoordenadasResource($coordenada->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Eliminar
     */
    public function destroy(Coordenadas $coordenada)
    {
        $coordenada->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
