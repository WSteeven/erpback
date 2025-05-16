<?php

namespace App\Http\Controllers\RecursosHumanos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\TipoDiscapacidadRequest;
use App\Http\Resources\RecursosHumanos\TipoDiscapacidadResource;
use App\Models\RecursosHumanos\TipoDiscapacidad;
use Src\Shared\Utils;

class TipoDiscapacidadController extends Controller
{
    private string $entidad = 'TipoDiscapacidad';
    public function __construct()
    {
//        $this->middleware('can:puede.ver.tipos_discapacidades')->only('index', 'show');
        $this->middleware('can:puede.crear.tipos_discapacidades')->only('store');
        $this->middleware('can:puede.editar.tipos_discapacidades')->only('update');
        $this->middleware('can:puede.eliminar.tipos_discapacidades')->only('update');
    }

    public function index()
    {
        $results = TipoDiscapacidad::ignoreRequest(['campos'])->filter()->get();
        $results = TipoDiscapacidadResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(TipoDiscapacidadRequest $request)
    {
        $tipo_discapacidad = TipoDiscapacidad::create($request->validated());
        $modelo = new TipoDiscapacidadResource($tipo_discapacidad);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(TipoDiscapacidad $tipo_discapacidad)
    {
        $modelo = new TipoDiscapacidadResource($tipo_discapacidad);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(TipoDiscapacidadRequest $request, TipoDiscapacidad $tipo_discapacidad)
    {
        $tipo_discapacidad->update($request->validated());
        $modelo = new TipoDiscapacidadResource($tipo_discapacidad->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(TipoDiscapacidad $tipo_discapacidad)
    {
        $tipo_discapacidad->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');

        return response()->json(compact('mensaje'));
    }
}

