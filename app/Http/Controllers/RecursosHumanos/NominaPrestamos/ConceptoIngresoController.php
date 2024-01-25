<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Models\RecursosHumanos\NominaPrestamos\ConceptoIngreso;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class ConceptoIngresoController extends Controller
{
    private $entidad = 'Concepto Ingreso';

    public function __construct()
    {
        $this->middleware('can:puede.ver.concepto_ingreso')->only('index', 'show');
        $this->middleware('can:puede.crear.concepto_ingreso')->only('store');
        $this->middleware('can:puede.editar.concepto_ingreso')->only('update');
        $this->middleware('can:puede.eliminar.concepto_ingreso')->only('destroy');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = ConceptoIngreso::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }
    public function show(Request $request, ConceptoIngreso $concepto_ingreso)
    {
        $modelo = $concepto_ingreso;
        return response()->json(compact('modelo'));
    }
    public function store(Request $request)
    {
        $concepto_ingreso = new ConceptoIngreso();
        $concepto_ingreso->nombre = $request->nombre;
        $concepto_ingreso->save();
        $modelo = $concepto_ingreso;
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }
    public function update(Request $request, ConceptoIngreso $concepto_ingreso)
    {
        $concepto_ingreso->nombre = $request->nombre;
        $concepto_ingreso->save();
        $modelo = $concepto_ingreso;
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));    }
    public function destroy(Request $request, ConceptoIngreso $concepto_ingreso)
    {
        $concepto_ingreso->delete();
        return response()->json(compact('concepto_ingreso'));
    }
}
