<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Models\RecursosHumanos\NominaPrestamos\Multas;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class MultaController extends Controller
{
    private $entidad = 'Multa';

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
        $datos = $request->validated();
        $multa = Multas::create($datos);
        $modelo = $multa;
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }
    public function update(Request $request, Multas $multa)
    {
        $datos = $request->validated();
        $multa->update($datos);
        $modelo = $multa;
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
        return $multa;
    }
    public function destroy(Request $request, Multas $multa)
    {
        $multa->delete();
        return response()->json(compact('multa'));
    }
}
