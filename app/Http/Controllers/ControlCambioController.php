<?php

namespace App\Http\Controllers;

use App\Http\Requests\ControlCambioRequest;
use App\Http\Resources\ControlCambioResource;
use App\Models\ControlCambio;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class ControlCambioController extends Controller
{
    private $entidad = 'Control de cambio';

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $results = ControlCambioResource::collection(ControlCambio::all());
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(ControlCambioRequest $request)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];

        // Respuesta
        $modelo = ControlCambio::create($datos);
        $modelo = new ControlCambioResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(ControlCambio $control_cambio)
    {
        $modelo = new ControlCambioResource($control_cambio);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(ControlCambioRequest $request, ControlCambio $control_cambio)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];

        // Respuesta
        $control_cambio->update($datos);
        $modelo = new ControlCambioResource($control_cambio->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Eliminar
     */
    public function destroy(ControlCambio $control_cambio)
    {
        $control_cambio->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
