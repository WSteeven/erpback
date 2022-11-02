<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubtareaRequest;
use App\Http\Resources\SubtareaResource;
use App\Models\Subtarea;
use App\Models\Tarea;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class SubtareaController extends Controller
{
    private $entidad = 'Subtarea';

    public function list(Request $request)
    {
        $estado = $request['estado'];

        if ($estado) {
            return SubtareaResource::collection(Subtarea::filter()->where('estado', $estado)->get());
        }

        return SubtareaResource::collection(Subtarea::filter()->get());
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        return response()->json(['results' => $this->list($request)]);
    }

    /**
     * Guardar
     */
    public function store(SubtareaRequest $request)
    {
        $tarea_id = $request['tarea_id'];

        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['grupo_id'] = $request->safe()->only(['grupo'])['grupo'];
        $datos['tipo_trabajo_id'] = $request->safe()->only(['tipo_trabajo'])['tipo_trabajo'];
        $datos['codigo_subtarea'] = Tarea::find($tarea_id)->codigo_tarea . '-' . (Subtarea::where('tarea_id', $tarea_id)->count() + 1);

        $datos['fecha_hora_creacion'] = Carbon::now();

        // Calcular estados
        $datos['estado'] = Subtarea::CREADO;

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

    public function asignar(Subtarea $subtarea)
    {
        $subtarea->estado = Subtarea::ASIGNADO;
        $subtarea->fecha_hora_asignacion = Carbon::now();
        $subtarea->save();
    }

    public function realizar(Subtarea $subtarea)
    {
        $subtarea->estado = Subtarea::REALIZADO;
        $subtarea->fecha_hora_realizado = Carbon::now();
        $subtarea->save();
    }
}
