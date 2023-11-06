<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Models\RecursosHumanos\NominaPrestamos\Multas;
use Illuminate\Http\Request;

class MultaController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:puede.ver.multa')->only('index', 'show');
        $this->middleware('can:puede.crear.multa')->only('store');
        $this->middleware('can:puede.editar.multa')->only('update');
        $this->middleware('can:puede.eliminar.multa')->only('update');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = Multas::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }
    public function show(Request $request, Multas $multum)
    {
        $modelo = $multum;
        return response()->json(compact('modelo'));
    }
    public function store(Request $request)
    {
        $multa = new Multas();
        $multa->nombre = $request->nombre;
        $multa->save();
        return $multa;
    }
    public function update(Request $request, Multas $multa)
    {
        $multa->nombre = $request->nombre;
        $multa->save();
        return $multa;
    }
    public function destroy(Request $request, Multas $multa)
    {
        $multa->delete();
        return response()->json(compact('multa'));
    }
}
