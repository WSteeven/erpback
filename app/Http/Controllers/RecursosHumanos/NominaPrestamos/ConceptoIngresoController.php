<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\ConceptoIngresoRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\ConceptoIngresoResource;
use App\Models\RecursosHumanos\NominaPrestamos\ConceptoIngreso;
use Src\Shared\Utils;

class ConceptoIngresoController extends Controller
{
    private string $entidad = 'Concepto Ingreso';

    public function __construct()
    {
        $this->middleware('can:puede.ver.concepto_ingreso')->only('index', 'show');
        $this->middleware('can:puede.crear.concepto_ingreso')->only('store');
        $this->middleware('can:puede.editar.concepto_ingreso')->only('update');
        $this->middleware('can:puede.eliminar.concepto_ingreso')->only('destroy');
    }

    public function index()
    {
        $results = ConceptoIngreso::ignoreRequest(['campos'])->filter()->get();
        $results = ConceptoIngresoResource::collection($results);
        return response()->json(compact('results'));
    }

    public function show(ConceptoIngreso $concepto_ingreso)
    {
        $modelo = $concepto_ingreso;
        return response()->json(compact('modelo'));
    }

    public function store(ConceptoIngresoRequest $request)
    {
        $datos = $request->validated();
        $concepto = ConceptoIngreso::create($datos);
        $modelo = new ConceptoIngresoResource($concepto);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function update(ConceptoIngresoRequest $request, ConceptoIngreso $concepto_ingreso)
    {
        $datos = $request->validated();
        $concepto_ingreso->update($datos);
        $modelo = new ConceptoIngresoResource($concepto_ingreso->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function destroy(ConceptoIngreso $concepto_ingreso)
    {
        $concepto_ingreso->delete();
        return response()->json(compact('concepto_ingreso'));
    }
}
