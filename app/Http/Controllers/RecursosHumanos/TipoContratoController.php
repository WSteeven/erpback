<?php

namespace App\Http\Controllers\RecursosHumanos;

use App\Http\Controllers\Controller;
use App\Models\TipoContrato;
use Illuminate\Http\Request;

class TipoContratoController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:puede.ver.tipo_contrato')->only('index', 'show');
        $this->middleware('can:puede.crear.tipo_contrato')->only('store');
        $this->middleware('can:puede.editar.tipo_contrato')->only('update');
        $this->middleware('can:puede.eliminar.tipo_contrato')->only('update');
    }

    public function index()
    {
        $results = [];
        $results = TipoContrato::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(Request $request)
    {
        $tipo_contrato = new TipoContrato();
        $tipo_contrato->nombre = $request->nombre;
        $tipo_contrato->save();
        return $tipo_contrato;
    }

    public function show(Request $request, TipoContrato $tipo_contrato)
    {
        return response()->json(compact('tipo_contrato'));
    }


    public function update(Request $request, TipoContrato $tipo_contrato)
    {
        $tipo_contrato->nombre = $request->nombre;
        $tipo_contrato->save();
        return $tipo_contrato;
    }

    public function destroy(Request $request, TipoContrato $tipo_contrato)
    {
        $tipo_contrato->delete();
        return response()->json(compact('tipo_contrato'));
    }

}
