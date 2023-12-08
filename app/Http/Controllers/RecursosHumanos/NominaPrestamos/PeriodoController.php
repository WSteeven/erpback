<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Models\RecursosHumanos\NominaPrestamos\Periodo;
use Illuminate\Http\Request;

class PeriodoController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:puede.ver.periodo')->only('index', 'show');
        $this->middleware('can:puede.crear.periodo')->only('store');
        $this->middleware('can:puede.editar.periodo')->only('update');
        $this->middleware('can:puede.eliminar.periodo')->only('update');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = Periodo::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }
    public function show(Request $request, Periodo $periodo)
    {
        return response()->json(compact('periodo'));
    }
    public function store(Request $request)
    {
        $periodo = new Periodo();
        $periodo->nombre = $request->nombre;
        $periodo->save();
        return $periodo;
    }
    public function update(Request $request, Periodo $periodo)
    {
        $periodo->nombre = $request->nombre;
        $periodo->save();
        return $periodo;
    }
    public function destroy(Request $request, Periodo $periodo)
    {
        $periodo->delete();
        return response()->json(compact('periodo'));
    }

}
