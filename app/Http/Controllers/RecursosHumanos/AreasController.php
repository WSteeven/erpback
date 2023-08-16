<?php

namespace App\Http\Controllers\RecursosHumanos;

use App\Http\Controllers\Controller;
use App\Models\EstadoCivil;
use App\Models\RecursosHumanos\Area;
use Illuminate\Http\Request;

class AreasController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:puede.ver.area')->only('index', 'show');
        $this->middleware('can:puede.crear.area')->only('store');
        $this->middleware('can:puede.editar.area')->only('update');
        $this->middleware('can:puede.eliminar.area')->only('update');
    }

    public function index()
    {
        $results = [];
        $results = Area::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(Request $request)
    {
        $tipo_contrato = new Area();
        $tipo_contrato->nombre = $request->nombre;
        $tipo_contrato->save();
        return $tipo_contrato;
    }

    public function show(Request $request, Area $tipo_contrato)
    {
        return response()->json(compact('tipo_contrato'));
    }


    public function update(Request $request, Area $tipo_contrato)
    {
        $tipo_contrato->nombre = $request->nombre;
        $tipo_contrato->save();
        return $tipo_contrato;
    }

    public function destroy(Request $request, Area $tipo_contrato)
    {
        $tipo_contrato->delete();
        return response()->json(compact('tipo_contrato'));
    }
}
