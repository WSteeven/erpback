<?php

namespace App\Http\Controllers\Tareas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tareas\TipoFotografiaRequest;
use App\Http\Resources\Tareas\TipoFotografiaResource;
use App\Models\Tareas\TipoFotografia;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class TipoFotografiaController extends Controller
{
    private string $entidad = 'Tipo de fotografía';

    /**
     * Listar
     */
    public function index()
    {
        $results = TipoFotografia::filter()->orderBy('id', 'desc')->get();
        $results = TipoFotografiaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(TipoFotografiaRequest $request)
    {
        $datos = $request->validated();

        $modelo = TipoFotografia::create($datos);
        $modelo = new TipoFotografiaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(TipoFotografia $tipo_fotografia)
    {
        $modelo = new TipoFotografiaResource($tipo_fotografia);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(TipoFotografiaRequest $request, TipoFotografia $tipo_fotografia)
    {
        $datos = $request->validated();

        $tipo_fotografia->update($datos);
        $modelo = new TipoFotografiaResource($tipo_fotografia->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Eliminar
     */
    public function destroy(TipoFotografia $tipo_fotografia)
    {
        $tipo_fotografia->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
