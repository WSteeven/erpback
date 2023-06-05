<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Models\RecursosHumanos\NominaPrestamos\DescuentosGenerales;
use Illuminate\Http\Request;

class DescuentosGeneralesController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:puede.ver.descuentos_generales')->only('index', 'show');
        $this->middleware('can:puede.crear.descuentos_generales')->only('store');
        $this->middleware('can:puede.editar.descuentos_generales')->only('update');
        $this->middleware('can:puede.eliminar.descuentos_generales')->only('update');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = DescuentosGenerales::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }
    public function show(Request $request, DescuentosGenerales $descuentos_generales)
    {
        return response()->json(compact('descuentos_generales'));
    }
    public function store(Request $request)
    {
        $descuentos_generales = new DescuentosGenerales();
        $descuentos_generales->nombre = $request->nombre;
        $descuentos_generales->save();
        return $descuentos_generales;
    }
    public function update(Request $request, DescuentosGenerales $descuentos_generales)
    {
        $descuentos_generales->nombre = $request->nombre;
        $descuentos_generales->save();
        return $descuentos_generales;
    }
    public function destroy(Request $request, DescuentosGenerales $descuentos_generales)
    {
        $descuentos_generales->delete();
        return response()->json(compact('descuentos_generales'));
    }
}
