<?php

namespace App\Http\Controllers;

use App\Http\Requests\TipoTareaRequest;
use App\Http\Resources\TipoTareaResource;
use App\Models\TipoTrabajo;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class TipoTareaController extends Controller
{
    private $entidad = 'Tipo de tarea';

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $cliente = $request['cliente'];
        $results = [];

        if ($cliente) {
            $results = TipoTareaResource::collection(TipoTrabajo::where('cliente_id', $cliente)->get());
        } else {
            $results = TipoTareaResource::collection(TipoTrabajo::all());
        }
        //$results = TipoTareaResource::collection(TipoTrabajo::all());
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(TipoTareaRequest $request)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];

        // Respuesta
        $modelo = TipoTrabajo::create($datos);
        $modelo = new TipoTareaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(TipoTrabajo $tipo_tarea)
    {
        $modelo = new TipoTareaResource($tipo_tarea);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(TipoTareaRequest $request, TipoTrabajo $tipo_tarea)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];

        // Respuesta
        $tipo_tarea->update($datos);
        $modelo = new TipoTareaResource($tipo_tarea->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Eliminar
     */
    public function destroy(TipoTrabajo $tipo_tarea)
    {
        $tipo_tarea->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
