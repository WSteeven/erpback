<?php

namespace App\Http\Controllers\RecursosHumanos\SeleccionContratacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\SeleccionContratacion\TipoPuestoTrabajoRequest;
use App\Http\Resources\RecursosHumanos\SeleccionContratacion\TipoPuestoTrabajoResource;
use App\Models\RecursosHumanos\SeleccionContratacion\TipoPuestoTrabajo;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class TipoPuestoTrabajoController extends Controller
{
    private $entidad = 'TipoPuestoTrabajo';
    public function __construct()
    {
        $this->middleware('can:puede.ver.rrhh_tipos_puestos')->only('index', 'show');
        $this->middleware('can:puede.crear.rrhh_tipos_puestos')->only('store');
        $this->middleware('can:puede.editar.rrhh_tipos_puestos')->only('update');
        $this->middleware('can:puede.eliminar.rrhh_tipos_puestos')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = TipoPuestoTrabajo::ignoreRequest(['campos'])->filter()->get();
        $results = TipoPuestoTrabajoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(TipoPuestoTrabajoRequest $request)
    {
        $tipo = TipoPuestoTrabajo::create($request->validated());
        $modelo = new TipoPuestoTrabajoResource($tipo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(TipoPuestoTrabajo $tipo)
    {
        $modelo = new TipoPuestoTrabajoResource($tipo);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(TipoPuestoTrabajoRequest $request, TipoPuestoTrabajo $tipo)
    {
        $tipo->update($request->validated());
        $modelo = new TipoPuestoTrabajoResource($tipo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(TipoPuestoTrabajo $tipo)
    {
        $tipo->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');

        return response()->json(compact('mensaje'));
    }
}
