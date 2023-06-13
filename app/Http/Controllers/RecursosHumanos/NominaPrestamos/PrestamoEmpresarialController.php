<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoEmpresarial;
use Illuminate\Http\Request;

class PrestamoEmpresarialController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:puede.ver.prestamoEmpresarial')->only('index', 'show');
        $this->middleware('can:puede.crear.prestamoEmpresarial')->only('store');
        $this->middleware('can:puede.editar.prestamoEmpresarial')->only('update');
        $this->middleware('can:puede.eliminar.prestamoEmpresarial')->only('update');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = PrestamoEmpresarial::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }
    public function show(Request $request, PrestamoEmpresarial $prestamoEmpresarial)
    {
        return response()->json(compact('prestamoEmpresarial'));
    }
    public function store(Request $request)
    {
        $datos = $request->validated();
        $prestamoEmpresarial=PrestamoEmpresarial::create($datos);
        return $prestamoEmpresarial;
    }
    public function update(Request $request, PrestamoEmpresarial $prestamoEmpresarial)
    {
        $prestamoEmpresarial->nombre = $request->nombre;
        $prestamoEmpresarial->save();
        return $prestamoEmpresarial;
    }
    public function destroy(Request $request, PrestamoEmpresarial $prestamoEmpresarial)
    {
        $prestamoEmpresarial->delete();
        return response()->json(compact('prestamoEmpresarial'));
    }

}
