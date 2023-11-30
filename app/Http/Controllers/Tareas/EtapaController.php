<?php

namespace App\Http\Controllers\Tareas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tareas\EtapaRequest;
use App\Http\Resources\Tareas\EtapaResource;
use App\Models\Tareas\Etapa;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class EtapaController extends Controller
{
    private $entidad = 'Etapa';

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $results  = Etapa::ignoreRequest(['campos'])->filter()->get();
        $results = EtapaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(EtapaRequest $request)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['proyecto_id'] = $request->safe()->only(['proyecto'])['proyecto'];
        $datos['responsable_id'] = $request->safe()->only(['responsable'])['responsable'];

        // Respuesta
        $modelo = Etapa::create($datos);
        $modelo = new EtapaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Etapa $etapa)
    {
        $modelo = new EtapaResource($etapa);
        return response()->json(compact('modelo'));
    }
    /**
     * Actualizar
     */
    public function update(EtapaRequest $request, Etapa $etapa)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['proyecto_id'] = $request->safe()->only(['proyecto'])['proyecto'];
        $datos['responsable_id'] = $request->safe()->only(['responsable'])['responsable'];

        // Respuesta
        $etapa->update($datos);
        $modelo = new EtapaResource($etapa);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(Etapa $etapa)
    {
        $etapa->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    /**
     * Desactivar
     */
    public function desactivar(Etapa $etapa)
    {
        $etapa->activo  = !$etapa->activo;
        $etapa->save();

        $modelo = new EtapaResource($etapa->refresh());
        return response()->json(compact('modelo'));
    }
}
