<?php

namespace App\Http\Controllers\Tareas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tareas\TipoCoordenadaRequest;
use App\Http\Resources\Tareas\TipoCoordenadaResource;
use App\Models\Tareas\TipoCoordenada;
use Src\Shared\Utils;

class TipoCoordenadaController extends Controller
{
    private string $entidad = 'Tipo de coordenada';

    /**
     * Listar
     */
    public function index()
    {
        $results = TipoCoordenada::filter()->orderBy('id', 'desc')->get();
        $results = TipoCoordenadaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(TipoCoordenadaRequest $request)
    {
        $datos = $request->validated();

        $modelo = TipoCoordenada::create($datos);
        $modelo = new TipoCoordenadaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(TipoCoordenada $tipo_coordenada)
    {
        $modelo = new TipoCoordenadaResource($tipo_coordenada);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(TipoCoordenadaRequest $request, TipoCoordenada $tipo_coordenada)
    {
        $datos = $request->validated();

        $tipo_coordenada->update($datos);
        $modelo = new TipoCoordenadaResource($tipo_coordenada->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Eliminar
     */
    public function destroy(TipoCoordenada $tipo_coordenada)
    {
        $tipo_coordenada->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
