<?php

namespace App\Http\Controllers\RecursosHumanos\SeleccionContratacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\SeleccionContratacion\TipoPuestoTrabajoRequest;
use App\Http\Resources\RecursosHumanos\SeleccionContratacion\TipoPuestoTrabajoResource;
use App\Models\RecursosHumanos\SeleccionContratacion\TipoPuesto;
use Illuminate\Http\JsonResponse;
use Src\Shared\Utils;

class TipoPuestoController extends Controller
{
    private string $entidad = 'TipoPuesto';
    public function __construct()
    {
        $this->middleware('can:puede.ver.rrhh_tipos_puestos')->only('index', 'show');
        $this->middleware('can:puede.crear.rrhh_tipos_puestos')->only('store');
        $this->middleware('can:puede.editar.rrhh_tipos_puestos')->only('update');
        $this->middleware('can:puede.eliminar.rrhh_tipos_puestos')->only('destroy');
    }

    public function index(): JsonResponse
    {
        $results = TipoPuesto::ignoreRequest(['campos'])->filter()->get();
        $results = TipoPuestoTrabajoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(TipoPuestoTrabajoRequest $request): JsonResponse
    {
        $tipo_puesto = TipoPuesto::create($request->validated());
        $modelo = new TipoPuestoTrabajoResource($tipo_puesto);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(TipoPuesto $tipo): JsonResponse
    {
        $modelo = new TipoPuestoTrabajoResource($tipo);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(TipoPuestoTrabajoRequest $request, TipoPuesto $tipo): JsonResponse
    {
        $tipo->update($request->validated());
        $modelo = new TipoPuestoTrabajoResource($tipo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(TipoPuesto $tipo): JsonResponse
    {
        $tipo->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');

        return response()->json(compact('mensaje'));
    }
}
