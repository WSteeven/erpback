<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Models\RecursosHumanos\NominaPrestamos\TipoLicencia;
use Illuminate\Http\Request;

class TipoLicenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:puede.ver.tipo_licencia')->only('index', 'show');
        $this->middleware('can:puede.crear.tipo_licencia')->only('store');
        $this->middleware('can:puede.editar.tipo_licencia')->only('update');
        $this->middleware('can:puede.eliminar.tipo_licencia')->only('update');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = TipoLicencia::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }
    public function show(Request $request, TipoLicencia $tipo_licencia)
    {
        return response()->json(compact('tipo_licencia'));
    }
    public function store(Request $request)
    {
        $tipo_licencia = new TipoLicencia();
        $tipo_licencia->nombre = $request->nombre;
        $tipo_licencia->save();
        return $tipo_licencia;
    }
    public function update(Request $request, TipoLicencia $tipo_licencia)
    {
        $tipo_licencia->nombre = $request->nombre;
        $tipo_licencia->save();
        return $tipo_licencia;
    }
    public function destroy(Request $request, TipoLicencia $tipo_licencia)
    {
        $tipo_licencia->delete();
        return response()->json(compact('tipo_licencia'));
    }
}
