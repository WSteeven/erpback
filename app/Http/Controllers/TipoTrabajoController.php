<?php

namespace App\Http\Controllers;

use App\Http\Requests\TipoTrabajoRequest;
use App\Http\Resources\TipoTrabajoResource;
use App\Models\TipoTrabajo;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class TipoTrabajoController extends Controller
{
    private $entidad = 'Tipo de trabajo';

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $cliente = $request['cliente'];
        $campos = explode(',', request('campos'));
        $results = [];
        // $page = $request['page'];

        /*if ($page) {
            $results = TipoTrabajo::simplePaginate($request['offset']);
            TipoTrabajoResource::collection($results);
        } else*/
        /*if ($cliente) {
            $results = TipoTrabajoResource::collection(TipoTrabajo::where('cliente_id', $cliente)->get());
        } else {*/

        if($campos) $results = TipoTrabajo::ignoreRequest(['campos'])->filter()->get($campos);
        else $results = TipoTrabajoResource::collection(TipoTrabajo::filter()->get());

        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(TipoTrabajoRequest $request)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];

        // Respuesta
        $modelo = TipoTrabajo::create($datos);
        $modelo = new TipoTrabajoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(TipoTrabajo $tipo_trabajo)
    {
        $modelo = new TipoTrabajoResource($tipo_trabajo);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(TipoTrabajoRequest $request, TipoTrabajo $tipo_trabajo)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];

        // Respuesta
        $tipo_trabajo->update($datos);
        $modelo = new TipoTrabajoResource($tipo_trabajo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Eliminar
     */
    public function destroy(TipoTrabajo $tipo_trabajo)
    {
        $tipo_trabajo->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
