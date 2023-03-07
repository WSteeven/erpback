<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProyectoRequest;
use App\Http\Resources\ProyectoResource;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class ProyectoController extends Controller
{
    private $entidad = 'Proyecto';

    public function index()
    {
        $cliente = request('cliente');
        $page = request('page');
        $results = [];

        $results = ProyectoResource::collection(Proyecto::ignoreRequest(['campos'])->filter()->get());


        return response()->json(compact('results'));
    }


    public function store(ProyectoRequest $request)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
        $datos['coordinador_id'] = $request->safe()->only(['coordinador'])['coordinador'];
        $datos['canton_id'] = $request->safe()->only(['canton'])['canton'];

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
