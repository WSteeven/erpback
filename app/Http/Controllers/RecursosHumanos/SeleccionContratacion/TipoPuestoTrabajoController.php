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
        $this->middleware('can:puede.ver.tipos_puestos_trabajos')->only('index', 'show');
        $this->middleware('can:puede.crear.tipos_puestos_trabajos')->only('store');
        $this->middleware('can:puede.editar.tipos_puestos_trabajos')->only('update');
        $this->middleware('can:puede.eliminar.tipos_puestos_trabajos')->only('update');
    }

    public function index()
    {
        $results = [];
        $results = TipoPuestoTrabajo::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(TipoPuestoTrabajoRequest $request)
    {
        $TipoPuestoTrabajo = TipoPuestoTrabajo::create($request->validated());
        $modelo = new TipoPuestoTrabajoResource($TipoPuestoTrabajo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(TipoPuestoTrabajo $TipoPuestoTrabajo)
    {
        $modelo = new TipoPuestoTrabajoResource($TipoPuestoTrabajo);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(TipoPuestoTrabajoRequest $request, TipoPuestoTrabajo $TipoPuestoTrabajo)
    {
        $TipoPuestoTrabajo->update($request->validated());
        $modelo = new TipoPuestoTrabajoResource($TipoPuestoTrabajo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(TipoPuestoTrabajo $TipoPuestoTrabajo)
    {
        $TipoPuestoTrabajo->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');

        return response()->json(compact('mensaje'));
    }
}
