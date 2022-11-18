<?php

namespace App\Http\Controllers;

use App\Http\Requests\TareaRequest;
use App\Http\Resources\TareaResource;
use App\Models\Tarea;
use App\Models\UbicacionTarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;
use stdClass;

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
     * Guardar - Coordinador
     */
    public function store(TareaRequest $request)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
        $datos['cliente_final_id'] = $request->safe()->only(['cliente_final'])['cliente_final'];
        $datos['supervisor_id'] = $request->safe()->only(['supervisor'])['supervisor'];
        $datos['coordinador_id'] = Auth::id();
        $datos['codigo_tarea'] = Tarea::latest('id')->first()->id + 1;

        $modelo = Tarea::create($datos);

        // Ubicacion tarea manual
        $ubicacionTarea = $request['ubicacion_tarea'];
        if ($ubicacionTarea && !$datos['cliente_final_id']) {
            $ubicacionTarea['provincia_id'] = $ubicacionTarea['provincia'];
            $ubicacionTarea['canton_id'] = $ubicacionTarea['canton'];
            $modelo->ubicacionTarea()->create($ubicacionTarea);
        }

        $modelo = new TareaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store', false);
        return response()->json(compact('mensaje', 'modelo'));
    }
    // Log::channel('testing')->info('Log', ['Ubicacion', $ubicacionTarea]);

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
        $datos['cliente_final_id'] = $request->safe()->only(['cliente_final'])['cliente_final'];
        $datos['supervisor_id'] = $request->safe()->only(['supervisor'])['supervisor'];
        $datos['coordinador_id'] = Auth::id();

        unset($datos['codigo_tarea']);
        $tarea->update($datos);

        // Ubicacion tarea manual
        $ubicacionTarea = $request['ubicacion_tarea'];
        if ($ubicacionTarea && !$datos['cliente_final_id']) {
            $ubicacionTarea['provincia_id'] = $ubicacionTarea['provincia'];
            $ubicacionTarea['canton_id'] = $ubicacionTarea['canton'];
            unset($ubicacionTarea['canton'], $ubicacionTarea['provincia']);
            if ($tarea->ubicacionTarea)
                $tarea->ubicacionTarea()->update($ubicacionTarea);
            else
                $tarea->ubicacionTarea()->create($ubicacionTarea);
        } else {
            $tarea->ubicacionTarea()->delete();
        }

        // Respuesta
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
