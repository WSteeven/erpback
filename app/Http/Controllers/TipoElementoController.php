<?php

namespace App\Http\Controllers;

use App\Http\Requests\TipoElementoRequest;
use App\Http\Resources\TipoElementoResource;
use App\Models\TipoElemento;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class TipoElementoController extends Controller
{
    private $entidad = 'Tipo de elemento';

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $results = TipoElementoResource::collection(TipoElemento::all());
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(TipoElementoRequest $request)
    {
        // Respuesta
        $modelo = TipoElemento::create($request->validated());
        $modelo = new TipoElementoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(TipoElemento $tipo_elemento)
    {
        $modelo = new TipoElementoResource($tipo_elemento);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(TipoElementoRequest $request, TipoElemento $tipo_elemento)
    {
        // Respuesta
        $tipo_elemento->update($request->validated());
        $modelo = new TipoElementoResource($tipo_elemento->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Eliminar
     */
    public function destroy(TipoElemento $tipo_elemento)
    {
        $tipo_elemento->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
