<?php

namespace App\Http\Controllers\RecursosHumanos;

use App\Http\Controllers\Controller;
use App\Models\EstadoCivil;
use Illuminate\Http\Request;

class EstadoCivilController extends Controller
{
    public function __construct()
    {
        // $this->middleware('can:puede.ver.estado_civil')->only('index', 'show');
        $this->middleware('can:puede.crear.estado_civil')->only('store');
        $this->middleware('can:puede.editar.estado_civil')->only('update');
        $this->middleware('can:puede.eliminar.estado_civil')->only('update');
    }

    public function index()
    {
        $results = [];
        $results = EstadoCivil::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(Request $request)
    {
        $tipo_contrato = new EstadoCivil();
        $tipo_contrato->nombre = $request->nombre;
        $tipo_contrato->save();
        return $tipo_contrato;
    }

    public function show(Request $request, EstadoCivil $tipo_contrato)
    {
        return response()->json(compact('tipo_contrato'));
    }


    public function update(Request $request, EstadoCivil $tipo_contrato)
    {
        $tipo_contrato->nombre = $request->nombre;
        $tipo_contrato->save();
        return $tipo_contrato;
    }

    public function destroy(Request $request, EstadoCivil $tipo_contrato)
    {
        $tipo_contrato->delete();
        return response()->json(compact('tipo_contrato'));
    }
}
