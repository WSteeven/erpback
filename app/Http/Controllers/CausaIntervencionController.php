<?php

namespace App\Http\Controllers;

use App\Http\Requests\CausaIntervencionRequest;
use App\Http\Resources\CausaIntervencionResource;
use App\Models\CausaIntervencion;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class CausaIntervencionController extends Controller
{
    private $entidad = 'Causa de intervención';

    public function index()
    {
        $results = CausaIntervencion::filter()->latest()->get();
        $results = CausaIntervencionResource::collection($results);

        return response()->json(compact('results'));
    }


    public function store(CausaIntervencionRequest $request)
    {
        $datos = $request->validated();

        $datos['tipo_trabajo_id'] = $request['tipo_trabajo'];

        $modelo = CausaIntervencion::create($datos);
        $modelo = new CausaIntervencionResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }


    public function show(CausaIntervencion $causa_intervencion)
    {
        $modelo = new CausaIntervencionResource($causa_intervencion);
        return response()->json(compact('modelo'));
    }


    public function update(CausaIntervencionRequest $request, CausaIntervencion $causa_intervencion)
    {
        $datos = $request->validated();

        $datos['tipo_trabajo_id'] = $request['tipo_trabajo'];

        $modelo = $causa_intervencion->update($datos);
        $modelo = new CausaIntervencionResource($causa_intervencion->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }
}
