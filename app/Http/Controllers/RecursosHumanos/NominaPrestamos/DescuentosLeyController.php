<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Models\RecursosHumanos\NominaPrestamos\DescuentosLey;
use Illuminate\Http\Request;

class DescuentosLeyController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:puede.ver.descuento_ley')->only('index', 'show');
        $this->middleware('can:puede.crear.descuento_ley')->only('store');
        $this->middleware('can:puede.editar.descuento_ley')->only('update');
        $this->middleware('can:puede.eliminar.descuento_ley')->only('update');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = DescuentosLey::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }
    public function show(Request $request, DescuentosLey $descuentos_ley)
    {
        return response()->json(compact('descuentos_ley'));
    }
    public function store(Request $request)
    {
        $descuentos_ley = new DescuentosLey();
        $descuentos_ley->nombre = $request->nombre;
        $descuentos_ley->save();
        return $descuentos_ley;
    }
    public function update(Request $request, DescuentosLey $descuentos_ley)
    {
        $descuentos_ley->nombre = $request->nombre;
        $descuentos_ley->save();
        return $descuentos_ley;
    }
    public function destroy(Request $request, DescuentosLey $descuentos_ley)
    {
        $descuentos_ley->delete();
        return response()->json(compact('descuentos_ley'));
    }
}
