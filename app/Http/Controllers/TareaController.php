<?php

namespace App\Http\Controllers;

use App\Http\Requests\TareaRequest;
use App\Http\Resources\TareaResource;
use App\Models\Tarea;
use App\Models\UbicacionTarea;
use App\Models\User;
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
        $campos = explode(',', $request['campos']);
        // $esCoordinador = Auth::user()->empleado->cargo->nombre == User::coor;
        $esCoordinador = User::find(Auth::id())->hasRole(User::ROL_COORDINADOR);

        if ($request['campos']) {
            if (!$esCoordinador) $results = Tarea::ignoreRequest(['campos'])->filter()->get($campos);
            if ($esCoordinador) $results = Tarea::ignoreRequest(['campos'])->filter()->porCoordinador()->get($campos);
        } else {
            if (!$esCoordinador) $results = Tarea::filter()->get();
            if ($esCoordinador) $results = Tarea::filter()->porCoordinador()->get();
        }

        $results = TareaResource::collection($results);

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
        $datos['proyecto_id'] = $request->safe()->only(['proyecto'])['proyecto'];
        $datos['fiscalizador_id'] = $request->safe()->only(['fiscalizador'])['fiscalizador'];
        // $datos['medio_notificacion'] = $request->safe()->only(['medio_notificacion'])['medio_notificacion'];
        $datos['coordinador_id'] = Auth::user()->empleado->id;
        $datos['codigo_tarea'] = 'TR' . Tarea::latest('id')->first()->id + 1;

        Log::channel('testing')->info('Log', ['Datos de Tarea antes de guardar', $datos]);

        $modelo = Tarea::create($datos);
        Log::channel('testing')->info('Log', ['Datos de Tarea despues de guardar', $datos]);
        // Ubicacion tarea manual
        /*$ubicacionTarea = $request['ubicacion_tarea'];
        if ($ubicacionTarea && !$datos['cliente_final_id']) {
            $ubicacionTarea['provincia_id'] = $ubicacionTarea['provincia'];
            $ubicacionTarea['canton_id'] = $ubicacionTarea['canton'];
            $modelo->ubicacionTarea()->create($ubicacionTarea);
        }*/

        $modelo = new TareaResource($modelo->refresh());
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
        $datos['fiscalizador_id'] = $request->safe()->only(['fiscalizador'])['fiscalizador'];
        // $datos['coordinador_id'] = Auth::id();
        $datos['proyecto_id'] = $request->safe()->only(['proyecto'])['proyecto'];

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
