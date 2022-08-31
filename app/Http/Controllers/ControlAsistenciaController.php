<?php

namespace App\Http\Controllers;

use App\Http\Requests\ControlAsistenciaRequest;
use App\Http\Resources\ControlAsistenciaResource;
use App\Models\ControlAsistencia;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class ControlAsistenciaController extends Controller
{
    private $entidad = 'Control de asistencia';

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $results = ControlAsistenciaResource::collection(ControlAsistencia::all());
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(ControlAsistenciaRequest $request)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];

        // Respuesta
        $modelo = ControlAsistencia::create($datos);
        $modelo = new ControlAsistenciaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(ControlAsistencia $control_asistencia)
    {
        $modelo = new ControlAsistenciaResource($control_asistencia);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(ControlAsistenciaRequest $request, ControlAsistencia $control_asistencia)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];

        // Respuesta
        $control_asistencia->update($datos);
        $modelo = new ControlAsistenciaResource($control_asistencia->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Eliminar
     */
    public function destroy(ControlAsistencia $control_asistencia)
    {
        $control_asistencia->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
