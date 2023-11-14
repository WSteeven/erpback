<?php

namespace App\Http\Controllers\RecursosHumanos;

use App\Http\Controllers\Controller;
use App\Models\RecursosHumanos\NominaPrestamos\Rubros;
use Illuminate\Http\Request;

class RubroController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:puede.ver.rubro')->only('index', 'show');
        $this->middleware('can:puede.crear.rubro')->only('store');
        $this->middleware('can:puede.editar.rubro')->only('update');
        $this->middleware('can:puede.eliminar.rubro')->only('update');
    }

    public function index()
    {
        $results = [];
        $results = Rubros::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }
    public function sueldo_basico(){
        $rubro = Rubros::where('nombre_rubro','Sueldo Basico')->first();
        return response()->json(compact('rubro'));
    }
public function porcentaje_iess(){
    $porcentaje_iess = Rubros::find(1) != null ? Rubros::find(1)->valor_rubro / 100 : 0;
    return response()->json(compact('porcentaje_iess'));
}
public function porcentaje_anticipo(){
    $rubro = Rubros::find(4) != null ? Rubros::find(4) : 0;
    return response()->json(compact('rubro'));
}
    public function store(Request $request)
    {
        $rubro = new Rubros();
        $rubro->nombre = $request->nombre;
        $rubro->save();
        return $rubro;
    }

    public function show(Request $request, Rubros $rubro)
    {
        $modelo = $rubro;
        return response()->json(compact('modelo'));
    }


    public function update(Request $request, Rubros $rubro)
    {
        $rubro->nombre = $request->nombre;
        $rubro->save();
        return $rubro;
    }

    public function destroy(Request $request, Rubros $rubro)
    {
        $rubro->delete();
        return response()->json(compact('rubro'));
    }

}
