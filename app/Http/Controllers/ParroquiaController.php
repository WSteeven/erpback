<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParroquiaRequest;
use App\Http\Resources\ParroquiaResource;
use App\Models\Parroquia;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class ParroquiaController extends Controller
{
    private $entidad = 'Parroquia';
    public function __construct()
    {
        $this->middleware('can:puede.ver.parroquias')->only('index', 'show');
        $this->middleware('can:puede.crear.parroquias')->only('store');
        $this->middleware('can:puede.editar.parroquias')->only('update');
        $this->middleware('can:puede.eliminar.parroquias')->only('destroy');
    }

    public function index()
    {
        $results = Parroquia::filter()->get();
        $results = ParroquiaResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(ParroquiaRequest $request)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['canton_id'] = $request->safe()->only(['canton'])['canton'];

        // Respuesta
        $modelo = Parroquia::create($datos);
        $modelo = new  ParroquiaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function show(Parroquia $parroquia)
    {
        $modelo = new ParroquiaResource($parroquia);
        return response()->json(compact('modelo'));
    }

    public function update(ParroquiaRequest $request, Parroquia $parroquia)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['canton_id'] = $request->safe()->only(['canton'])['canton'];

        // Respuesta
        $parroquia->update($datos);
        $modelo = new  ParroquiaResource($parroquia->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function destroy(Parroquia $parroquia)
    {
        $parroquia->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
