<?php

namespace App\Http\Controllers;

use App\Http\Requests\RutaTareaRequest;
use App\Http\Resources\RutaTareaResource;
use App\Models\RutaTarea;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class RutaTareaController extends Controller
{
    private $entidad = 'Ruta';

    /**
     * Listar
     */
    public function index(Request $request)
    {
        // $cliente = $request['cliente'];
        $results = RutaTareaResource::collection(RutaTarea::filter()->get());

        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(RutaTareaRequest $request)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];

        // Respuesta
        $modelo = RutaTarea::create($datos);
        $modelo = new RutaTareaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(RutaTarea $ruta_tarea)
    {
        $modelo = new RutaTareaResource($ruta_tarea);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(RutaTareaRequest $request, RutaTarea $ruta_tarea)
    {
        if ($request->isMethod('patch')) {
            $ruta_tarea->update($request->except(['id']));
        }

        // Adaptacion de foreign keys
        // $datos = $request->validated();
        // $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];

        // Respuesta
        // $ruta_tarea->update($datos);
        $modelo = new RutaTareaResource($ruta_tarea->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Eliminar
     */
    public function destroy(RutaTarea $ruta_tarea)
    {
        $ruta_tarea->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
