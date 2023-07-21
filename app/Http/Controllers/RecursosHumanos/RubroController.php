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
    public function store(Request $request)
    {
        $tipo_contrato = new Rubros();
        $tipo_contrato->nombre = $request->nombre;
        $tipo_contrato->save();
        return $tipo_contrato;
    }

    public function show(Request $request, Rubros $tipo_contrato)
    {
        return response()->json(compact('tipo_contrato'));
    }


    public function update(Request $request, Rubros $tipo_contrato)
    {
        $tipo_contrato->nombre = $request->nombre;
        $tipo_contrato->save();
        return $tipo_contrato;
    }

    public function destroy(Request $request, Rubros $tipo_contrato)
    {
        $tipo_contrato->delete();
        return response()->json(compact('tipo_contrato'));
    }

}
