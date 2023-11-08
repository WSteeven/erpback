<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProyectoRequest;
use App\Http\Resources\ProyectoResource;
use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Src\Shared\Utils;

class ProyectoController extends Controller
{
    private $entidad = 'Proyecto';

    public function listar()
    {
        $campos = request('campos') ? explode(',', request('campos')) : '*';
        if (auth()->user()->hasRole([User::ROL_JEFE_TECNICO]))
            return Proyecto::ignoreRequest(['campos', 'coordinador_id'])->filter()->get($campos);
        return Proyecto::ignoreRequest(['campos'])->filter()->get($campos);
    }

    public function index()
    {
        $results = $this->listar();
        if (!request('campos')) $results = ProyectoResource::collection($results);
        return response()->json(compact('results'));
    }


    public function store(ProyectoRequest $request)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
        $datos['canton_id'] = $request->safe()->only(['canton'])['canton'];

        // $esCoordinador = Auth::user()->hasRole(User::ROL_COORDINADOR);
        $esJefeTecnico = Auth::user()->hasRole(User::ROL_JEFE_TECNICO);
        $datos['coordinador_id'] = $esJefeTecnico ? $request->safe()->only(['coordinador'])['coordinador'] :  Auth::user()->empleado->id;

        // Respuesta
        $modelo = Proyecto::create($datos);
        $modelo = new ProyectoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }


    public function show(Proyecto $proyecto)
    {
        $modelo = new ProyectoResource($proyecto);
        return response()->json(compact('modelo'));
    }


    public function update(ProyectoRequest $request, Proyecto $proyecto)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
        $datos['coordinador_id'] = $request->safe()->only(['coordinador'])['coordinador'];
        $datos['canton_id'] = $request->safe()->only(['canton'])['canton'];

        // Respuesta
        $proyecto->update($datos);
        $modelo = new ProyectoResource($proyecto->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }


    public function destroy(Proyecto $proyecto)
    {
        $proyecto->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
