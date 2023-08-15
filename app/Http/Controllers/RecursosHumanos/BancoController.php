<?php

namespace App\Http\Controllers\RecursosHumanos;

use App\Http\Controllers\Controller;
use App\Models\RecursosHumanos\Banco;
use Illuminate\Http\Request;

class BancoController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:puede.ver.banco')->only('index', 'show');
        $this->middleware('can:puede.crear.banco')->only('store');
        $this->middleware('can:puede.editar.banco')->only('update');
        $this->middleware('can:puede.eliminar.banco')->only('update');
    }

    public function index()
    {
        $results = [];
        $results = Banco::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(Request $request)
    {
        $tipo_contrato = new Banco();
        $tipo_contrato->nombre = $request->nombre;
        $tipo_contrato->save();
        return $tipo_contrato;
    }

    public function show(Request $request, Banco $tipo_contrato)
    {
        return response()->json(compact('tipo_contrato'));
    }


    public function update(Request $request, Banco $tipo_contrato)
    {
        $tipo_contrato->nombre = $request->nombre;
        $tipo_contrato->save();
        return $tipo_contrato;
    }

    public function destroy(Request $request, Banco $tipo_contrato)
    {
        $tipo_contrato->delete();
        return response()->json(compact('tipo_contrato'));
    }
}
