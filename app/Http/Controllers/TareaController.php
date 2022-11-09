<?php

namespace App\Http\Controllers;

use App\Http\Requests\TareaRequest;
use App\Http\Resources\TareaResource;
use App\Models\Tarea;
use App\Models\UbicacionTarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Src\Shared\Utils;

class TareaController extends Controller
{
    private $entidad = 'Tarea';

    /**
     * Listar
     */
    public function index(Request $request)
    {
        /*$results = TareaResource::collection(Tarea::all());
        return response()->json(compact('results'));*/
        $page = $request['page'];

        if ($page) {
            $results = Tarea::simplePaginate($request['offset']);
            TareaResource::collection($results);
        } else {
            $results = TareaResource::collection(Tarea::filter()->get());
        }

        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(TareaRequest $request)
    {
        // Adaptacion de foreign keys
        // $datos['coordinador_id'] = $request->safe()->only(['coordinador'])['coordinador'];
        $datos = $request->validated();
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
        $datos['cliente_final_id'] = $request->safe()->only(['cliente_final'])['cliente_final'];
        $datos['supervisor_id'] = $request->safe()->only(['supervisor'])['supervisor'];
        $datos['codigo_tarea'] = Tarea::latest('id')->first()->id + 1;
        $datos['coordinador_id'] = Auth::id();

        // Respuesta
        $modelo = Tarea::create($datos);

        // Ubicacion tarea
        $ubicacionTarea = $request['ubicacion_tarea'];
        $ubicacionTarea['provincia_id'] = $ubicacionTarea['provincia'];
        $ubicacionTarea['canton_id'] = $ubicacionTarea['canton'];
        $modelo->ubicacionesTareas()->create($ubicacionTarea);
        /* $datos['provincia_id'] = $request->safe()->only(['provincia'])['provincia'];
        $datos['canton_id'] = $request->safe()->only(['canton'])['canton']; */

        $modelo = new TareaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store', false);
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Tarea $tarea)
    {
        $modelo = new TareaResource($tarea);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(TareaRequest $request, Tarea $tarea)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];

        // Respuesta
        $tarea->update($datos);
        $modelo = new TareaResource($tarea->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update', false);
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Eliminar
     */
    public function destroy(Tarea $tarea)
    {
        $tarea->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy', false);
        return response()->json(compact('mensaje'));
    }
}
