<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubtareaRequest;
use App\Http\Resources\SubtareaResource;
use App\Models\Subtarea;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class SubtareaController extends Controller
{
    private $entidad = 'Subtarea';

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $tarea = $request['tarea'];
        $results = [];

        if ($tarea) {
            $results = SubtareaResource::collection(Subtarea::where('tarea_id', $tarea)->get());
        } else {
            $results = SubtareaResource::collection(Subtarea::all());
        }

        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(SubtareaRequest $request)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];

        // Respuesta
        $modelo = Subtarea::create($datos);
        $modelo = new SubtareaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Subtarea $subtarea)
    {
        $modelo = new SubtareaResource($subtarea);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(SubtareaRequest $request, Subtarea $subtarea)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];

        // Respuesta
        $subtarea->update($datos);
        $modelo = new SubtareaResource($subtarea->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Eliminar
     */
    public function destroy(Subtarea $subtarea)
    {
        $subtarea->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
